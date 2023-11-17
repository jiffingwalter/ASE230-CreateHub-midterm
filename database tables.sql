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
    date_created datetime,
    last_edited datetime
	);

create table attached_to(  -- posts and attachment relationship set
    attachment_id int,
    post_id int
);

create table attachments(
    aid int primary key,
	file_path varchar(255),
    type varchar(32),
    tmp_name varchar(128),
    size int,
    ext varchar(5)
);

create table post_tags(  -- post & tags relationship set
    post_id int primary key,
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