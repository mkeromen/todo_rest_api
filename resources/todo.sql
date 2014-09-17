DROP TABLE IF EXISTS todo_list;
CREATE TABLE todo_list (
  id INT NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique todo ID.',
  name varchar(255) COMMENT 'A collection of data to cache.',
  date_create int(11) NOT NULL DEFAULT '0' COMMENT 'A Unix timestamp indicate creation date of todo.',
  date_update int(11) NOT NULL DEFAULT '0' COMMENT 'A Unix timestamp indicate update date of todo.',
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS todo_items;
CREATE TABLE todo_items (
  id INT NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique todo item ID.',
  todo_list_id INT NOT NULL COMMENT 'Foreign Key: reference to todo list.',
  description TEXT COMMENT 'Description for the item.',
  is_read BOOL NOT NULL DEFAULT '0' COMMENT 'Mark an item as read.',
  is_done BOOL NOT NULL DEFAULT '0' COMMENT 'Mark an item as done.',
  PRIMARY KEY (id),
  CONSTRAINT fk_todoListID FOREIGN KEY (todo_list_id)
  REFERENCES todo_list(id)
  ON DELETE CASCADE
);
