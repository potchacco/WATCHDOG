-- Add image column to pets table
ALTER TABLE pets
ADD COLUMN image_url VARCHAR(255) DEFAULT NULL AFTER gender;