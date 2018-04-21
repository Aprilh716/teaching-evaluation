
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

/*---------------------------------------------------------------------------
 *  NAME: grade
 *  DESC: 班级
 *---------------------------------------------------------------------------*/
CREATE TABLE grade (
  gid bigint unsigned NOT NULL default 0,
  major varchar(64) not null default '',
  grade int unsigned not null default 1,
  created_at timestamp not null default 0,
  updated_at timestamp not null default 0,
  PRIMARY KEY (gid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*---------------------------------------------------------------------------
 *  NAME: lesson
 *  DESC: 课程信息
 *---------------------------------------------------------------------------*/
CREATE TABLE lesson (
  lid int unsigned NOT NULL AUTO_INCREMENT,
  name varchar(128) not null default '',
  tid int unsigned not null default 0,
  teacher_uid int unsigned not null default 0,
  total_score int unsigned not null default 0,
  created_at timestamp not null default 0,
  updated_at timestamp not null default 0,
  key(teacher_uid),
  key(tid),
  PRIMARY KEY (lid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*---------------------------------------------------------------------------
 *  NAME: lesson_type
 *  DESC: 课程类型
 *---------------------------------------------------------------------------*/
CREATE TABLE lesson_type (
  tid int unsigned not null AUTO_INCREMENT,
  name varchar(128) not null default '',
  questions json DEFAULT NULL COMMENT '课程题目',
  created_at timestamp not null default 0,
  updated_at timestamp not null default 0,
  PRIMARY KEY (tid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*---------------------------------------------------------------------------
 *  NAME: student_lesson
 *  DESC: 学生选课表
 *---------------------------------------------------------------------------*/
CREATE TABLE student_lesson(
  id int unsigned not null AUTO_INCREMENT,
  uid bigint unsigned not null default 0,
  lid bigint unsigned not null default 0,
  score int unsigned not null default 0,
  score_status tinyint unsigned not null default 0,
  score_json json default null,
  created_at timestamp not null default 0,
  updated_at timestamp not null default 0,
  unique key (uid, lid),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;