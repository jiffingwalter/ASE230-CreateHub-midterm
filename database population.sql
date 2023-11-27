drop table users;
drop table user_posts;
drop table posts;
drop table attached_to;
drop table attachments;
drop table post_tags;
drop table tags;
drop table portfolios;
drop table user_portfolios;
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
	uid int,
    pid int
);
    
create table posts(
    pid int primary key,
    title varchar(255),
    content longtext,
    has_attachment int(1),
    date_created datetime,
    last_edited datetime
	);

create table attached_to(  -- posts and attachment relationship set
    aid int,
    pid int
);

create table attachments(
    aid int primary key,
	file_name varchar(255),
    ext varchar(6),
    size int,
    type varchar(32),
    date_created datetime
);

create table post_tags(  -- post & tags relationship set
    tid int,
    pid int
	);

create table tags(
	tid int primary key,
    tag varchar(128)
	);

create table portfolios(
    fid int primary key,
    name varchar(128),
    category varchar(32),
    images longtext
    );

create table user_portfolios(
    uid int,
    fid int
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

-- create users
insert into users values (000000,'UNDEFINED','template@createhub.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','0000-00-00 00:00:00',1);
insert into users values (000001,'ADMIN','admin@createhub.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','0000-00-00 00:00:00',0);
insert into users values (983282,'test account 1','test1@email.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','2023-10-15 09:46:44',1);
insert into users values (520790,'test account 2','test2@email.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','2023-10-20 11:52:49',1);

insert into posts values(000000,'POST TITLE','POST CONTENT',0,'0000-00-00','0000-00-00 00:00:00am');
insert into user_posts values (0,0); 

-- create test posts, attachments, and tags
insert into posts values(770308,'testing new posts','test post body',1,'2023-10-30 12:00:30','2023-11-08 03:15:23');
insert into attachments values (123456,'20231019183921_2.jpg','jpg',149728,'image/jpeg','2023-10-30 12:00:30');
insert into tags values (0,'red dead redemption 2');
insert into tags values (3,'horses');
insert into user_posts values (520790,770308); 
insert into attached_to values(123456,770308);
insert into post_tags values (0,770308);
insert into post_tags values (3,770308);

insert into posts values(944839,'test post','post bod',1,'2023-10-16 03:24:04','2023-11-08 03:15:23');
insert into attachments values (468516,'Cave_Women.PNG','png',149728,'image/png','2023-10-16 03:24:04');
insert into user_posts values (983282,944839); 
insert into attached_to values(468516,944839);

insert into posts values(475467,'testing for posts','thank you for viewing this post',1,'2023-10-16 12:00:30','2023-10-16 12:00:30');
insert into attachments values (985456,'3c75d20.jpg','jpg',2899318,'image/jpeg','2023-10-16 12:00:30');
insert into tags values (1,'cool');
insert into tags values (2,'art');
insert into user_posts values (983282,475467); 
insert into attached_to values(985456,475467);
insert into post_tags values (1,475467);
insert into post_tags values (2,475467);

insert into posts values(328885,'','',1,'2023-11-09 12:00:30','2023-11-18 03:15:23');
insert into attachments values (569485,'2023-04-20.png','png',149728,'image/png','2023-11-09 12:00:30');
insert into user_posts values (520790,328885); 
insert into attached_to values(569485,328885);

insert into posts values(000001,'user 1s post','she sells sea shells by the sea shore',1,'2023-10-14 16:05:00','2023-10-14 06:34:23');
insert into tags values (4,'test');
insert into tags values (5,'post');
insert into tags values (6,'wee doggy');
insert into user_posts values (000001,000001);
insert into post_tags values (4,000001);
insert into post_tags values (5,000001);
insert into post_tags values (6,000001);

insert into posts values(123903,'cool picture','this picture is cool',1,'2023-10-15 11:43:05','2023-10-15 11:43:05');
insert into attachments values (456735,'2RP5BgZ.jpg','jpg',520855,'image/jpeg','2023-10-15 11:43:05');
insert into tags values (7,'this');
insert into tags values (8,'is');
insert into tags values (9,'a');
insert into tags values (10,'nice');
insert into tags values (11,'picture');
insert into user_posts values (000001,123903); 
insert into attached_to values(456735,123903);
insert into post_tags values (7,123903);
insert into post_tags values (8,123903);
insert into post_tags values (9,123903);
insert into post_tags values (10,123903);
insert into post_tags values (11,123903);

-- create user test portfolios
insert into portfolios values (123456,'Test portfolio','Work','["DV4pD0pVAAUVWFf.png","feelsbetterman.jpg","Kronk_.jpg"]');
insert into user_portfolios values (983282,123456);

insert into portfolios values (234567,'another Portfolio','Images','["gort.jpg","patproj.jpg"]');
insert into user_portfolios values (983282,234567);

insert into portfolios values (894567,'Resume','Art','["astrowave wallpaper.jpg"]');
insert into user_portfolios values (983282,894567);