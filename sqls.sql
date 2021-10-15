create table bank_manager (
    bank_manager_id int(6) not null auto_increment,
    client_number int(10) not null,
    firstname varchar(128) not null,
    givenname varchar(128) not null,
    password varchar(255) not null,
    mobile bigint(20),
    email varchar(128) not null,
    register_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (bank_manager_id)
    ) ENGINE=InnoDB;

create table business_account (
    business_account_id int(6) not null auto_increment,
    bank_manager_id int(6) not null,
    client_number int(10) not null,
    firstname varchar(128) not null,
    givenname varchar(128) not null,
    password varchar(255) not null,
    mobile bigint(20),
    email varchar(128) not null,
    register_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (business_account_id),
    FOREIGN KEY (bank_manager_id) REFERENCES bank_manager(bank_manager_id)
) ENGINE=InnoDB;

create table savings_account (
    savings_account_id int(6) not null auto_increment,
    bank_manager_id int(6) not null,
    client_number int(10) not null,
    firstname varchar(128) not null,
    givenname varchar(128) not null,
    password varchar(255) not null,
    mobile bigint(20),
    email varchar(128) not null,
    register_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (savings_account_id),
    FOREIGN KEY (bank_manager_id) REFERENCES bank_manager(bank_manager_id)
) ENGINE=InnoDB;


ALTER TABLE business_account
ADD UNIQUE (client_number);