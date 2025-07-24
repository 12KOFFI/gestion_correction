ALTER TABLE professeur
ADD COLUMN etablissement_id INT,
ADD FOREIGN KEY (etablissement_id) REFERENCES etablissement(id);
