jQuery.noConflict();
jQuery(document).ready(function($) {
    
    const API_URL = 'fetch_data.php';
    const UPDATE_URL = 'update_task.php';

    let chartStatus = null, chartSolicitante = null, chartCategoria = null, chartPrioridade = null;
    let usersLoaded = false, statusLoaded = false, typesLoaded = false, prioritiesLoaded = false;
    let globalStatusList = [], globalPriorityList = [];
    
    const pbiColors = ['#0078D4', '#E3008C', '#F2C811', '#107C10', '#D83B01', '#8764B8', '#B4009E', '#5C2D91'];

    $('#statusTrigger').on('click', function(e) { e.stopPropagation(); $('#statusContent').toggleClass('show'); });
    $('#statusContent').on('click', function(e) { e.stopPropagation(); });
    $(document).on('click', function(e) { if (!$(e.target).closest('.custom-dropdown').length) $('#statusContent').removeClass('show'); });
    $(document).on('change', '.status-checkbox', updateStatusLabel);
    $('#btnMarkAll').on('click', function(e){ e.preventDefault(); $('.status-checkbox').prop('checked', true); updateStatusLabel(); });
    $('#btnUnmarkAll').on('click', function(e){ e.preventDefault(); $('.status-checkbox').prop('checked', false); updateStatusLabel(); });

    function updateStatusLabel() {
        let total = $('.status-checkbox').length, checked = $('.status-checkbox:checked').length;
        let text = (checked === 0) ? "Nenhum selecionado" : (checked === total ? "Todos" : checked + " selecionados");
        $('#statusText').text(text);
    }

    function fetchData() {
        $('#applyFiltersBtn').css('opacity', '0.5').text('Carregando...');
        $.ajax({
            url: API_URL, method: 'POST', data: getFilterData(), dataType: 'json',
            success: function(r) {
                $('#applyFiltersBtn').css('opacity', '1').text('Aplicar Filtros');
                if (r.error) {
                    alert('Erro: ' + r.error);
                } else {
                    globalStatusList = r.lista_status || [];
                    globalPriorityList = r.lista_prioridades || [];
                    updateDashboard(r);
                }
            },
            error: function() { $('#applyFiltersBtn').css('opacity', '1').text('Erro'); }
        });
    }

    function getFilterData() {
        let sel = [];
        $('.status-checkbox:checked').each(function() { sel.push($(this).val()); });
        let total = $('.status-checkbox').length;
        let stStr = (sel.length > 0 && sel.length < total) ? sel.join(',') : "";

        return {
            usuario_id: $('#usuario_id').val(),
            // Filtros de Gerencia e Profissional removidos daqui
            tipo_id: $('#tipo_id').val(),
            prioridade_id: $('#prioridade_id').val(),
            data_inicio: $('#reg_data_inicio').val() || formatDateISO($('#data_inicio').val()),
            data_fim: $('#reg_data_fim').val() || formatDateISO($('#data_fim').val()),
            status_id: stStr,
            usar_periodo: $('#usar_periodo').is(':checked')
        };
    }

    function formatDateISO(d) { if(!d) return ''; let p=d.split('/'); return (p.length===3)?`${p[2]}-${p[1]}-${p[0]}`:d; }

    function updateDashboard(d) {
        populateUserFilter(d.lista_usuarios); 
        // Funções de popular Gerencia e Profissional removidas
        populateStatusFilter(d.lista_status); 
        populateTypeFilter(d.lista_tipos); 
        populatePriorityFilter(d.lista_prioridades);
        calculateKPIs(d.tasks); updateTaskTable(d.tasks); updateCharts(d.tasks); updateSummaryTable(d.tasks);
    }

    function populateUserFilter(u) {
        if(usersLoaded || !u) return;
        let sel = $('#usuario_id'), curr = sel.val(); sel.empty().append('<option value="0">Todos os Solicitantes</option>');
        u.forEach(i => sel.append(`<option value="${i.usuario_id}">${i.usuario_login}</option>`));
        if(curr && curr!="0") sel.val(curr); usersLoaded = true;
    }

    function populateStatusFilter(s) {
        if(statusLoaded || !s) return;
        let c = $('#statusListContainer'); c.empty();
        s.forEach(i => c.append(`<div class="checkbox-item"><input type="checkbox" class="status-checkbox" value="${i.id}" id="chk_${i.id}" checked><label for="chk_${i.id}">${i.nome}</label></div>`));
        updateStatusLabel(); statusLoaded = true;
    }

    function populateTypeFilter(t) {
        if(typesLoaded || !t) return;
        let sel = $('#tipo_id'), curr = sel.val();
        sel.empty().append('<option value="0">Todos</option>');
        t.forEach(i => sel.append(`<option value="${i.id}">${i.nome}</option>`));
        if(curr && curr!="0") sel.val(curr); typesLoaded = true;
    }

    function populatePriorityFilter(p) {
        if(prioritiesLoaded || !p) return;
        let sel = $('#prioridade_id'), curr = sel.val();
        sel.empty().append('<option value="all">Todas</option>');
        p.forEach(i => sel.append(`<option value="${i.id}">${i.nome}</option>`));
        if(curr && curr!="all") sel.val(curr); prioritiesLoaded = true;
    }

    function calculateKPIs(t) {
        if(!t) return;
        let total=t.length, ini=0, and=0, par=0, con=0;
        t.forEach(x => {
            let s = (x.status||'').toLowerCase();
            if(s.includes('conclu')||s.includes('finaliz')||s.includes('entregue')) con++;
            else if(s.includes('andamento')||s.includes('execu')) and++;
            else if(s.includes('paralisada')||s.includes('suspensa')) par++;
            else ini++;
        });
        $('#kpi-total').text(total); $('#kpi-andamento').text(and); $('#kpi-iniciar').text(ini); $('#kpi-paralisada').text(par); $('#kpi-concluidas').text(con);
    }

    function updateTaskTable(t) {
        if($.fn.DataTable.isDataTable('#taskTable')) $('#taskTable').DataTable().destroy();
        let grp = {};
        if(t) t.forEach(x => {
            let n = x.projeto||'Sem Projeto';
            if(!grp[n]) grp[n] = {projeto:n, solicitante:x.solicitante, qtd:0, subs:[]};
            grp[n].qtd++; grp[n].subs.push(x);
        });
        let dt = Object.values(grp);
        let tbl = $('#taskTable').DataTable({
            data: dt,
            columns: [{className:'dt-control',orderable:false,data:null,defaultContent:''}, {data:'projeto',title:'Projeto'}, {data:'qtd',title:'Qtd.'}, {data:'solicitante',title:'Solicitante'}],
            language: {url:"//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"}, 
            pageLength: 10, dom: 'tp', order:[[1,'asc']]
        });
        $('#taskTable tbody').off('click','td.dt-control').on('click','td.dt-control', function(){
            let tr=$(this).closest('tr'), row=tbl.row(tr);
            if(row.child.isShown()){ row.child.hide(); tr.removeClass('shown'); }
            else { row.child(fmtChild(row.data())).show(); tr.addClass('shown'); }
        });
    }

    function fmtChild(d) {
        let headerPrio = (typeof USER_CAN_EDIT !== 'undefined' && USER_CAN_EDIT) ? 'Prioridade (Editar)' : 'Prioridade';
        let h = `<table class="child-table"><thead><tr><th>Demanda</th><th>Categoria</th><th>Status</th><th>${headerPrio}</th><th>Fim</th></tr></thead><tbody>`;
        
        d.subs.forEach(x => {
            let c='#ccc', s=(x.status||'').toLowerCase();
            if(s.includes('conclu')||s.includes('finaliz')||s.includes('entregue')) c='#107C10';
            else if(s.includes('andamento')||s.includes('execu')) c='#F2C811';
            else if(s.includes('paralisada')||s.includes('suspensa')) c='#D83B01';
            else if(s.includes('iniciar')) c='#0078D4';
            else c='#888';
            let statusHtml = `<span class="status-dot" style="background-color:${c}"></span>${x.status}`;

            let prioHtml = '';
            if (typeof USER_CAN_EDIT !== 'undefined' && USER_CAN_EDIT) {
                prioHtml = `<select class="edit-select edit-priority" data-id="${x.id}">`;
                globalPriorityList.forEach(p => {
                    let selected = (x.prioridade === p.nome) ? 'selected' : '';
                    prioHtml += `<option value="${p.id}" ${selected}>${p.nome}</option>`;
                });
                prioHtml += `</select>`;
            } else {
                prioHtml = x.prioridade;
            }

            let desc = x.descricao ? x.descricao : 'Sem descrição.';
            let descSafe = desc.replace(/"/g, '&quot;'); 
            let tooltipHtml = `<span class="tooltip-trigger" data-desc="${descSafe}">${x.demanda}</span>`;

            h += `<tr><td>${tooltipHtml}</td><td>${x.categoria}</td><td>${statusHtml}</td><td>${prioHtml}</td><td>${x.data}</td></tr>`;
        });
        return h+'</tbody></table>';
    }

    $(document).on('mouseenter', '.tooltip-trigger', function(e) {
        let text = $(this).data('desc');
        $('#global-tooltip-body').text(text);
        $('#global-tooltip').show();
        moveTooltip(e);
    });

    $(document).on('mousemove', '.tooltip-trigger', function(e) { moveTooltip(e); });
    $(document).on('mouseleave', '.tooltip-trigger', function() { $('#global-tooltip').hide(); });

    function moveTooltip(e) {
        let t = $('#global-tooltip');
        let x = e.clientX + 15; let y = e.clientY + 15;
        if (x + t.width() > $(window).width()) x = e.clientX - t.width() - 15;
        if (y + t.height() > $(window).height()) y = e.clientY - t.height() - 15;
        t.css({ top: y + 'px', left: x + 'px' });
    }

    $(document).on('change', '.edit-priority', function() {
        updateTask($(this).data('id'), 'prioridade', $(this).val());
    });

    function updateTask(id, type, value) {
        $.ajax({
            url: UPDATE_URL, method: 'POST',
            data: { id: id, tipo: type, valor: value }, dataType: 'json',
            success: function(r) { if(r.success) fetchData(); else alert('Erro ao atualizar: ' + r.error); },
            error: function() { alert('Erro de conexão ao tentar atualizar.'); }
        });
    }

    function updateSummaryTable(t) {
        if($.fn.DataTable.isDataTable('#summaryTable')) $('#summaryTable').DataTable().destroy();
        let cnt={}, tot=t?t.length:0; if(t) t.forEach(x => { let n=x.solicitante||'N/A'; cnt[n]=(cnt[n]||0)+1; });
        let bod = $('#summaryTable tbody').empty();
        Object.keys(cnt).forEach(n => bod.append(`<tr><td>${n}</td><td>${cnt[n]}</td><td>${tot>0?((cnt[n]/tot)*100).toFixed(1)+'%':'0%'}</td></tr>`));
        $('#summaryTable').DataTable({paging:false, searching:false, info:false, order:[[1,'desc']]});
    }

    function updateCharts(t) {
        if(!t) return;
        let sc={}; t.forEach(x => { let s=x.status||'Não informado'; sc[s]=(sc[s]||0)+1; });
        if(chartStatus) chartStatus.destroy();
        chartStatus = new Chart(document.getElementById('statusChart'), { type:'doughnut', data:{labels:Object.keys(sc), datasets:[{data:Object.values(sc), backgroundColor:pbiColors}]}, options:{responsive:true,maintainAspectRatio:false, cutout:'60%', plugins:{legend:{position:'right'}}} });

        let lc={}; t.forEach(x => { let n=x.solicitante||'N/A'; lc[n]=(lc[n]||0)+1; });
        let srt = Object.entries(lc).sort((a,b)=>b[1]-a[1]).slice(0,10);
        if(chartSolicitante) chartSolicitante.destroy();
        chartSolicitante = new Chart(document.getElementById('solicitanteChart'), { type:'bar', data:{labels:srt.map(i=>i[0]), datasets:[{label:'Qtd', data:srt.map(i=>i[1]), backgroundColor:'#0078D4', borderRadius:4}]}, options:{indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{grid:{display:false}}}} });

        let cc={}; t.forEach(x => { let c=x.categoria||'Geral'; cc[c]=(cc[c]||0)+1; });
        let scc = Object.entries(cc).sort((a,b)=>b[1]-a[1]);
        if(chartCategoria) chartCategoria.destroy();
        chartCategoria = new Chart(document.getElementById('categoriaChart'), { type:'bar', data:{labels:scc.map(i=>i[0]), datasets:[{label:'Qtd', data:scc.map(i=>i[1]), backgroundColor:'#B4009E', borderRadius:4}]}, options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{grid:{display:false}}}} });

        let pc={}; t.forEach(x => { let p=x.prioridade||'Normal'; pc[p]=(pc[p]||0)+1; });
        let ord=['Muito Baixa','Baixa','Normal','Alta','Muito Alta'], pl=[], pd=[], pcl=[];
        ord.forEach(k => { if(pc[k]) { pl.push(k); pd.push(pc[k]); pcl.push(k.includes('Baixa')?'#107C10':(k.includes('Normal')?'#F2C811':'#D83B01')); } });
        if(chartPrioridade) chartPrioridade.destroy();
        chartPrioridade = new Chart(document.getElementById('prioridadeChart'), { type:'bar', data:{labels:pl, datasets:[{label:'Qtd', data:pd, backgroundColor:pcl, borderRadius:4, barPercentage:0.6}]}, options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{grid:{display:false}}}} });
    }

    $('#data_inicio, #data_fim').datepicker({dateFormat:'dd/mm/yy', onSelect:function(d){ let p=d.split('/'); $(this).next('input').val(`${p[2]}-${p[1]}-${p[0]}`); }});
    $('#applyFiltersBtn').on('click', fetchData);
    fetchData(); 
});