ALTER TABLE professeur
ADD COLUMN etablissement_id INT DEFAULT NULL,
ADD CONSTRAINT fk_professeur_etablissement
FOREIGN KEY (etablissement_id) REFERENCES etablissement(id)
ON DELETE SET NULL;
