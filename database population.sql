drop table users;
drop table user_posts;
drop table posts;
drop table attached_to;
drop table attachments;
drop table post_tags;
drop table tags;
drop table portfolio;
drop table roles;

create table users(
    uid int primary key,
    name varchar(32),
    email varchar(64),
    password varchar(64),
    date_created datetime,
    role int
    );
    
create table user_posts( -- user & posts relationship set
	user_id int,
    post_id int
);
    
create table posts(
    pid int primary key,
    title varchar(64),
    content longtext,
    has_attachment int(1),
    date_created datetime,
    last_edited datetime
	);

create table attached_to(  -- posts and attachment relationship set
    attachment_id int,
    post_id int
);

create table attachments(
    aid int primary key,
	file_name varchar(255),
    ext varchar(5),
    size int,
    type varchar(32)
);

create table post_tags(  -- post & tags relationship set
    post_id int primary key,
    tag_id int(10)
	);

create table tags(
	id int primary key,
    tag varchar(128)
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

-- DATABASE TEST POPULATION --

-- create roles
insert into roles values (0,'Admin');
insert into roles values (1,'User');
insert into roles values (2,'Verified');

-- create test users
insert into users values (000000,'UNDEFINED','template@createhub.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','0000-00-00 00:00:00',1);
insert into users values (000001,'ADMIN','admin@createhub.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','0000-00-00 00:00:00',0);
insert into users values (983282,'test account 1','test1@email.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','2023-10-15 09:46:44',1);
insert into users values (520790,'test account 2','test2@email.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','2023-10-20 11:52:49',1);

insert into posts values(000000,'POST TITLE','POST CONTENT',0,'0000-00-00','0000-00-00 00:00:00am');

-- create test post, attachment, and tags
insert into posts values(770308,'testing new posts','test post body',1,'2023-10-30 12:00:30','2023-11-08 03:15:23');
insert into attachments values (123456,'20231019183921_2.jpg','jpg',149728,'image/jpeg');
insert into tags values (0,'red dead redemption 2');
insert into user_posts values (520790,770308); 
insert into attached_to values(123456,770308);
