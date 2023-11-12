create table users(
    id int primary key,
    name varchar(32),
    email varchar(64),
    password varchar(64),
    date_created date,
    role int
    );
    
create table posts(
    pid int primary key,
    title varchar(64),
    content longtext,
    author int(6),
    date_created date,
    last_edited date
	);

create table attachments(
	file_path varchar(255),
    type varchar(32),
    tmp_name varchar(128),
    size int,
    ext varchar(5)
);
    
create table post_tags(
    id int primary key,
    tag_id int(10)
	);

create table tags(
	id int primary key,
    tag varchar(32)
	);

create table portfolio(
    author int primary key,
    name varchar(128),
    category varchar(32),
    images varchar(128)
    );

create table roles(
	id int primary key,
    name varchar(32)
);