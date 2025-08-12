CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `gender` varchar(10) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);
  -- drop data_process table currently i am not using queue system. i"m currently directly saving the order in the database.
-- CREATE TABLE data_process(
--     process_id INT NOT NULL AUTO_INCREMENT,
--     process_status ENUM('queue','processing','completed','failed')NOT NULL DEFAULT 'queue',
--     request_data JSON NOT NULL,
--     queued_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     PRIMARY KEY(process_id)
--     );



INSERT INTO `users` (`id`, `name`, `address`, `phone`, `email`, `display_order`) VALUES
(1, 'Pawan', 'Noida, uttar-pradesh', '9199208167', 'pawan@gmail.com', 0),
(2, 'Neeraj', 'Delhi, Dwarika', '8967878976', 'neeraj@gmail.com', 1),
(3, 'Rohit', 'Delhi, Dwarka', '9876543210', 'rohit@gmail.com', 2),
(4, 'Ravi', 'Delhi, Dwarka', '9876543210', 'ravi@gmail.com', 3),
(5, 'Rahul', 'Delhi, Dwarka', '9876543210', 'rahul@gmail.com', 4),
(6, 'Ajay', 'Delhi, Dwarka', '9876543210', 'ajay@gmail.com', 5),
(7, 'Suresh', 'Delhi, Dwarka', '9876543210', 'suresh@gmail.com', 6),
(8, 'Vikash', 'Patna, Bihar', '9693059418', 'vikash@gmail.com', 7);