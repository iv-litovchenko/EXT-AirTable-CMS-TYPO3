CREATE TABLE tx_mytable (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    description text,
    image blob,
    flag tinyint(1) unsigned DEFAULT '0' NOT NULL,
    number1 int(11) DEFAULT '0' NOT NULL,
    number2 double DEFAULT '0' NOT NULL,
    latitude double(13,10) DEFAULT '0.0000000000' NOT NULL,
    date timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY whatever (uid,number1)
);
