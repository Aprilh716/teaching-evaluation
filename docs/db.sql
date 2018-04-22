
/*---------------------------------------------------------------------------
 *  NAME: user
 *  DESC: 用户
 *---------------------------------------------------------------------------*/
CREATE TABLE user (
  uid int unsigned NOT NULL AUTO_INCREMENT,
  name varchar(32) not null default '',
  code int unsigned not null,
  password  varchar(32) DEFAULT NULL COMMENT '密码',
  role tinyint unsigned not null default 0,
  grade_id int not null default 0,
  created_at timestamp not null default CURRENT_TIMESTAMP,
  updated_at timestamp not null default CURRENT_TIMESTAMP,
  PRIMARY KEY (uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

初始化管理员
insert into user(name,code,password,role) values('admin',10086,md5('111111'),3);

insert into user(name,code,password,role) VALUES
('赵二傻',20101010,md5('20101010'),2),
('王大花',20101011,md5('20101011'),2),
('钱小娟',20101012,md5('20101012'),2),
('孙富贵',20101013,md5('20101013'),2),
('李黑子',20101014,md5('20101014'),2),
('周进财',20101015,md5('20101015'),2),
('杨燕子',20101016,md5('20101016'),2);

insert into user(name,code,password,role,grade_id) VALUES
('吴大山',2014110001,md5('2014110001'),1,1),
('张大宝',2014110002,md5('2014110002'),1,2),
('王淑芬',2014110003,md5('2014110003'),1,1),
('王玉芬',2014110004,md5('2014110004'),1,1),
('李美丽',2014110005,md5('2014110005'),1,1),
('李春华',2014110006,md5('2014110006'),1,2),
('张二狗',2014110007,md5('2014110007'),1,2);

/*---------------------------------------------------------------------------
 *  NAME: grade
 *  DESC: 班级
 *---------------------------------------------------------------------------*/
CREATE TABLE grade (
  gid bigint unsigned NOT NULL AUTO_INCREMENT,
  major varchar(64) not null default '',
  grade int unsigned not null default 1,
  created_at timestamp not null default CURRENT_TIMESTAMP,
  updated_at timestamp not null default CURRENT_TIMESTAMP,
  PRIMARY KEY (gid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

初始化班级信息
insert into grade(major,grade) values
('信息与计算科学',141),
('信息与计算科学',142),
('数学',141),
('数学',142),
('工商管理',141),
('工商管理',142),
('软件工程',141),
('软件工程',142);


/*---------------------------------------------------------------------------
 *  NAME: lesson
 *  DESC: 课程类型-对应评教题目
 *---------------------------------------------------------------------------*/
CREATE TABLE lesson (
  lid bigint unsigned NOT NULL AUTO_INCREMENT,
  name varchar(128) not null default '' COMMENT '课程名称',
  questions varchar(128) not null default '' COMMENT '课程题目，question.qid 逗号拼接',
  created_at timestamp not null default CURRENT_TIMESTAMP,
  updated_at timestamp not null default CURRENT_TIMESTAMP,
  PRIMARY KEY (lid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

初始化课程信息
insert into lesson(name) VALUES
('高数1'),('高数2'),('大学英语1'),('大学英语2'),
('大学英语3'),('大学英语4'),('思想品德与修养'),('近代史'),
('大学物理1'),('大学物理2'),('C语言程序设计'),('数据结构与算法'),
('概率论'),('运筹学'),('线性代数'),('计算机网络'),
('现代密码学'),('大学生恋爱指导'),('会计学');


/*---------------------------------------------------------------------------
 *  NAME: lesson_teacher_grade
 *  DESC: 课程信息
 *---------------------------------------------------------------------------*/
CREATE TABLE lesson_teacher_grade (
  id int unsigned NOT NULL AUTO_INCREMENT,
  lid int unsigned not null default 0 COMMENT '课程id',
  teacher_uid int unsigned not null default 0 COMMENT '教师用户id',
  gid int unsigned not null default 0 COMMENT '班级id',
  created_at timestamp not null default CURRENT_TIMESTAMP,
  updated_at timestamp not null default CURRENT_TIMESTAMP,
  key(teacher_uid),
  key(lid),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------------------
 *  NAME: question
 *  DESC: 评教题目
 *---------------------------------------------------------------------------*/
CREATE TABLE question(
  qid int unsigned not null AUTO_INCREMENT,
  type tinyint unsigned not null default 0 COMMENT '0 选择题:4 非常优秀; 3 优秀; 2 一般; 1 很差; 1 主观题',
  description VARCHAR(255) not null default '' COMMENT '题目描述',
  created_at timestamp not null default CURRENT_TIMESTAMP,
  updated_at timestamp not null default CURRENT_TIMESTAMP,
  PRIMARY KEY (qid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

初始化题目信息
insert into question(description) values
('教学质量咋样'),('课堂氛围好不'),('课后解答');

/*---------------------------------------------------------------------------
 *  NAME: student_answer
 *  DESC: 学生评教
 *---------------------------------------------------------------------------*/
CREATE TABLE student_answer(
  id int unsigned not null AUTO_INCREMENT,
  lesson_teacher_grade_id int unsigned not null default 0,
  answer json default null,
  sorce bigint unsigned not null default 0 COMMENT '评教总分',
  created_at timestamp not null default CURRENT_TIMESTAMP,
  updated_at timestamp not null default CURRENT_TIMESTAMP,
  key(lesson_teacher_grade_id),
  key(sorce),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;