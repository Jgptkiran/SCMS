-- Insert data into the facilities table
INSERT INTO facilities (Name, Description, HourlyRate, Image)
VALUES
('Basketball Court', 'A full-sized basketball court with standard hoops and markings.', 50.00, 'basketball_court.jpg'),
('Futsal', 'Indoor futsal court with artificial turf suitable for 5-a-side games.', 40.00, 'futsal.jpg'),
('Swimming Pool', 'Olympic-size swimming pool with lanes for lap swimming.', 30.00, 'swimming_pool.jpg'),
('Lawn Tennis Court', 'Outdoor lawn tennis court with professional-grade turf.', 60.00, 'tennis_court.jpg');

-- Update the facilities table with the file paths for images
UPDATE facilities
SET Image = 'uploads/basketball_court.jpg'
WHERE Name = 'Basketball Court';

UPDATE facilities
SET Image = 'uploads/futsal.jpg'
WHERE Name = 'Futsal';

UPDATE facilities
SET Image = 'uploads/swimming_pool.jpg'
WHERE Name = 'Swimming Pool';

UPDATE facilities
SET Image = 'uploads/tennis_court.jpg'
WHERE Name = 'Lawn Tennis Court';

ALTER TABLE bookings MODIFY slots VARCHAR(255) NOT NULL;
