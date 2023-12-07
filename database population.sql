drop table users;
drop table posts;
drop table attachments;
drop table post_tags;
drop table tags;
drop table portfolios;
drop table roles;

create table users(
    uid int primary key auto_increment,
    name varchar(32),
    email varchar(64),
    password varchar(64),
    date_created datetime,
    role tinyint
    );
    
create table posts(
    pid int primary key auto_increment,
    author int,
    title varchar(255),
    content longtext,
    has_attachment tinyint(1),
    date_created datetime,
    last_edited datetime
	);

create table attachments(
    aid int primary key auto_increment,
    pid int,
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
	tid int primary key auto_increment,
    tag varchar(128)
	);

create table portfolios(
    fid int primary key auto_increment,
    author int,
    name varchar(128),
    category varchar(32),
    images longtext
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

-- create users and test post
insert into users (name,email,password,date_created,role) values ('UNDEFINED','template@createhub.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','0000-00-00 00:00:00',1);

insert into users (name,email,password,date_created,role) values ('ADMIN','admin@createhub.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','0000-00-00 00:00:00',0);
insert into portfolios (author,name,category,images)
    values (2,'another Portfolio','Images','gort.jpg,patproj.jpg');

insert into users (name,email,password,date_created,role) values ('test account 1','test1@email.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','2023-10-15 09:46:44',1);
insert into portfolios (author,name,category,images)
    values (3,'Test portfolio','Work','DV4pD0pVAAUVWFf.png,feelsbetterman.jpg,Kronk_.jpg');

insert into users (name,email,password,date_created,role) values ('test account 2','test2@email.com','$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m','2023-10-20 11:52:49',1);
insert into portfolios (author,name,category,images)
    values (4,'Resume','Art','astrowave wallpaper.jpg');

insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 1
    values (1,'POST TITLE','POST CONTENT',0,'0000-00-00','0000-00-00 00:00:00am');

-- create test posts, attachments, and tags
insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 2
	values(2,'testing new posts','test post body',1,'2023-10-30 12:00:30','2023-11-08 03:15:23');
insert into attachments (pid,file_name,ext,size,type,date_created)
	values (2,'20231019183921_2.jpg','jpg',149728,'image/jpeg','2023-10-30 12:00:30');
insert into tags (tag) values ('picture');
insert into tags (tag) values ('horse');
insert into post_tags (tid,pid) values (1,2);
insert into post_tags values (2,2);

insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 3
    values (3,'test post','post bod',1,'2023-10-16 03:24:04','2023-11-08 03:15:23');
insert into attachments (pid,file_name,ext,size,type,date_created)
	values (3,'Cave_Women.PNG','png',149728,'image/png','2023-10-16 03:24:04');

insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 4
    values (3,'testing for posts','thank you for viewing this post',1,'2023-10-16 12:00:30','2023-10-16 12:00:30');
insert into attachments (pid,file_name,ext,size,type,date_created)
	values (4,'3c75d20.jpg','jpg',2899318,'image/jpeg','2023-10-16 12:00:30');
insert into tags (tag) values ('cool');
insert into tags (tag) values ('art');
insert into post_tags (tid,pid) values (3,4);
insert into post_tags (tid,pid) values (4,4);

insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 5
    values (4,'','',1,'2023-11-09 12:00:30','2023-11-18 03:15:23');
insert into attachments (pid,file_name,ext,size,type,date_created)
	values (5,'2023-04-20.png','png',149728,'image/png','2023-11-09 12:00:30');

insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 6
    values (4,'user 1s post','she sells sea shells by the sea shore',1,'2023-10-14 16:05:00','2023-10-14 06:34:23');
insert into tags (tag) values ('test');
insert into tags (tag) values ('post');
insert into tags (tag) values ('wee doggy');
insert into post_tags (tid,pid) values (5,6);
insert into post_tags (tid,pid) values (6,6);
insert into post_tags (tid,pid) values (7,6);

insert into posts (author,title,content,has_attachment,date_created,last_edited) -- pid 7
    values (4,'cool picture','this picture is cool',1,'2023-10-15 11:43:05','2023-10-15 11:43:05');
insert into attachments (pid,file_name,ext,size,type,date_created)
	values (7,'2RP5BgZ.jpg','jpg',520855,'image/jpeg','2023-10-15 11:43:05');
insert into tags (tag) values ('this');
insert into tags (tag) values ('is');
insert into tags (tag) values ('a');
insert into tags (tag) values ('nice');
insert into post_tags (tid,pid) values (8,7);
insert into post_tags (tid,pid) values (9,7);
insert into post_tags (tid,pid) values (10,7);
insert into post_tags (tid,pid) values (11,7);
insert into post_tags (tid,pid) values (1,7);
