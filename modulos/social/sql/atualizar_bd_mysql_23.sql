SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE social_acao_arquivo ADD COLUMN social_acao_arquivo_nome_real VARCHAR(255) DEFAULT NULL;
ALTER TABLE social_acao_arquivo ADD COLUMN social_acao_arquivo_local VARCHAR(255) DEFAULT NULL;
ALTER TABLE social_acao_arquivo ADD COLUMN social_acao_arquivo_tamanho INTEGER(100) UNSIGNED DEFAULT NULL;