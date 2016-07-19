ALTER TABLE `events` CHANGE `ev_begin_date` `ev_begin_date` DATE NULL DEFAULT NULL COMMENT 'Дата начала';
ALTER TABLE `events` CHANGE `ev_end_date` `ev_end_date` DATE NOT NULL COMMENT 'Дата окночания';
ALTER TABLE `events` ADD `ev_begin_time` TIME NOT NULL COMMENT 'Время начала' AFTER `ev_begin_date`;
ALTER TABLE `events` ADD `ev_end_time` TIME NOT NULL COMMENT 'Время окончания' AFTER `ev_end_date`;