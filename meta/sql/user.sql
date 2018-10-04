use swoole;
create table IF NOT EXISTS user (
   `id` int(11) primary key auto_increment comment '主键',
   `name` varchar(10) not null default '' comment '',
   `age` int(10) not null default '0' comment '年龄',
   `gender` varchar(10) not null default '男' comment '性别',
   `ctime` int(11) not null default '0' comment '表的创建时间',
   `mtime` int(11) not null default '0' comment '表的最后修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
