/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  duncan.ewan
 * Created: 23 Jun 2017
 */

create database radius;

#
# Table structure for table 'radgroupcheck'
#

CREATE TABLE radius.radgroupcheck (
  id int(11) unsigned NOT NULL auto_increment,
  GroupName varchar(64) NOT NULL default '',
  Attribute varchar(64)  NOT NULL default '',
  op char(2) NOT NULL DEFAULT '==',
  Value varchar(253)  NOT NULL default '',
  PRIMARY KEY  (id),
  KEY GroupName (GroupName(32))
)  ENGINE = InnoDB ROW_FORMAT = DEFAULT;

#
# Table structure for table 'radcheck'
#

CREATE TABLE radius.radcheck (
  id int(11) unsigned NOT NULL auto_increment,
  UserName varchar(64) NOT NULL default '',
  Attribute varchar(64)  NOT NULL default '',
  op char(2) NOT NULL DEFAULT '==',
  Value varchar(253) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY UserName (UserName(32))
)  ENGINE = InnoDB ROW_FORMAT = DEFAULT;

#
# Table structure for table 'radpostauth'
#

CREATE TABLE radius.radpostauth (
  id int(11) NOT NULL auto_increment,
  user varchar(64) NOT NULL default '',
  pass varchar(64) NOT NULL default '',
  reply varchar(32) NOT NULL default '',
  date timestamp(14) NOT NULL,
  PRIMARY KEY  (id)
)  ENGINE = InnoDB ROW_FORMAT = DEFAULT;

create database powerdns;

CREATE TABLE powerdns.domains (
  id                    INT AUTO_INCREMENT,
  name                  VARCHAR(255) NOT NULL,
  master                VARCHAR(128) DEFAULT NULL,
  last_check            INT DEFAULT NULL,
  type                  VARCHAR(6) NOT NULL,
  notified_serial       INT DEFAULT NULL,
  account               VARCHAR(40) DEFAULT NULL,
  PRIMARY KEY (id)
) Engine=InnoDB;
CREATE UNIQUE INDEX name_index ON powerdns.domains(name);

CREATE TABLE powerdns.records (
  id                    BIGINT AUTO_INCREMENT,
  domain_id             INT DEFAULT NULL,
  name                  VARCHAR(255) DEFAULT NULL,
  type                  VARCHAR(10) DEFAULT NULL,
  content               VARCHAR(64000) DEFAULT NULL,
  ttl                   INT DEFAULT NULL,
  prio                  INT DEFAULT NULL,
  change_date           INT DEFAULT NULL,
  disabled              TINYINT(1) DEFAULT 0,
  ordername             VARCHAR(255) BINARY DEFAULT NULL,
  auth                  TINYINT(1) DEFAULT 1,
  PRIMARY KEY (id)
) Engine=InnoDB;

CREATE INDEX nametype_index ON powerdns.records(name,type);
CREATE INDEX domain_id ON powerdns.records(domain_id);
CREATE INDEX recordorder ON powerdns.records (domain_id, ordername);


INSERT INTO radius.radcheck
(username, attribute, op, value) 
VALUES ('username1', 'attribute1', 'op1', 'value1');
INSERT INTO radius.radcheck
(username, attribute, op, value) 
VALUES ('username2', 'attribute2', 'op2', 'value2');
INSERT INTO radius.radcheck
(username, attribute, op, value) 
VALUES ('username3', 'attribute3', 'op3', 'value3');
INSERT INTO radius.radcheck
(username, attribute, op, value) 
VALUES ('username4', 'attribute4', 'op4', 'value4');
INSERT INTO radius.radcheck
(username, attribute, op, value) 
VALUES ('username5', 'attribute5', 'op5', 'value5');

INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname1', 'attribute1', 'op1', 'value1');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname2', 'attribute2', 'op2', 'value2');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname3', 'attribute3', 'op3', 'value3');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname4', 'attribute4', 'op4', 'value4');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname5', 'attribute5', 'op5', 'value5');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname6', 'attribute6', 'op6', 'value6');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname7', 'attribute7', 'op7', 'value7');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname8', 'attribute8', 'op8', 'value8');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname9', 'attribute9', 'op9', 'value9');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname10', 'attribute10', 'op10', 'value10');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname11', 'attribute11', 'op11', 'value11');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname12', 'attribute12', 'op12', 'value12');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname13', 'attribute13', 'op13', 'value13');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname14', 'attribute14', 'op14', 'value14');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname15', 'attribute15', 'op15', 'value15');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname16', 'attribute16', 'op16', 'value16');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname17', 'attribute17', 'op17', 'value17');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname18', 'attribute18', 'op18', 'value18');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname19', 'attribute19', 'op19', 'value19');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname20', 'attribute20', 'op20', 'value20');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname21', 'attribute21', 'op21', 'value21');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname22', 'attribute22', 'op22', 'value22');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname23', 'attribute23', 'op23', 'value23');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname24', 'attribute24', 'op24', 'value24');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname25', 'attribute25', 'op25', 'value25');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname26', 'attribute26', 'op26', 'value26');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname27', 'attribute27', 'op27', 'value27');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname28', 'attribute28', 'op28', 'value28');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname29', 'attribute29', 'op29', 'value29');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname30', 'attribute30', 'op30', 'value30');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname31', 'attribute31', 'op31', 'value31');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname32', 'attribute32', 'op32', 'value32');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname33', 'attribute33', 'op33', 'value33');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname34', 'attribute34', 'op34', 'value34');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname35', 'attribute35', 'op35', 'value35');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname36', 'attribute36', 'op36', 'value36');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname37', 'attribute37', 'op37', 'value37');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname38', 'attribute38', 'op38', 'value38');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname39', 'attribute39', 'op39', 'value39');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname40', 'attribute40', 'op40', 'value40');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname41', 'attribute41', 'op41', 'value41');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname42', 'attribute42', 'op42', 'value42');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname43', 'attribute43', 'op43', 'value43');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname44', 'attribute44', 'op44', 'value44');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname45', 'attribute45', 'op45', 'value45');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname46', 'attribute46', 'op46', 'value46');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname47', 'attribute47', 'op47', 'value47');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname48', 'attribute48', 'op48', 'value48');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname49', 'attribute49', 'op49', 'value49');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname50', 'attribute50', 'op50', 'value50');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname51', 'attribute51', 'op51', 'value51');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname52', 'attribute52', 'op52', 'value52');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname53', 'attribute53', 'op53', 'value53');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname54', 'attribute54', 'op54', 'value54');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname55', 'attribute55', 'op55', 'value55');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname56', 'attribute56', 'op56', 'value56');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname57', 'attribute57', 'op57', 'value57');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname58', 'attribute58', 'op58', 'value58');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname59', 'attribute59', 'op59', 'value59');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname60', 'attribute60', 'op60', 'value60');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname61', 'attribute61', 'op61', 'value61');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname62', 'attribute62', 'op62', 'value62');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname63', 'attribute63', 'op63', 'value63');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname64', 'attribute64', 'op64', 'value64');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname65', 'attribute65', 'op65', 'value65');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname66', 'attribute66', 'op66', 'value66');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname67', 'attribute67', 'op67', 'value67');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname68', 'attribute68', 'op68', 'value68');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname69', 'attribute69', 'op69', 'value69');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname70', 'attribute70', 'op70', 'value70');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname71', 'attribute71', 'op71', 'value71');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname72', 'attribute72', 'op72', 'value72');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname73', 'attribute73', 'op73', 'value73');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname74', 'attribute74', 'op74', 'value74');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname75', 'attribute75', 'op75', 'value75');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname76', 'attribute76', 'op76', 'value76');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname77', 'attribute77', 'op77', 'value77');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname78', 'attribute78', 'op78', 'value78');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname79', 'attribute79', 'op79', 'value79');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname80', 'attribute80', 'op80', 'value80');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname81', 'attribute81', 'op81', 'value81');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname82', 'attribute82', 'op82', 'value82');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname83', 'attribute83', 'op83', 'value83');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname84', 'attribute84', 'op84', 'value84');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname85', 'attribute85', 'op85', 'value85');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname86', 'attribute86', 'op86', 'value86');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname87', 'attribute87', 'op87', 'value87');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname88', 'attribute88', 'op88', 'value88');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname89', 'attribute89', 'op89', 'value89');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname90', 'attribute90', 'op90', 'value90');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname91', 'attribute91', 'op91', 'value91');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname92', 'attribute92', 'op92', 'value92');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname93', 'attribute93', 'op93', 'value93');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname94', 'attribute94', 'op94', 'value94');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname95', 'attribute95', 'op95', 'value95');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname96', 'attribute96', 'op96', 'value96');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname97', 'attribute97', 'op97', 'value97');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname98', 'attribute98', 'op98', 'value98');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname99', 'attribute99', 'op99', 'value99');
INSERT INTO radius.radgroupcheck (groupname, attribute, op, value) VALUES ('groupname100', 'attribute100', 'op100', 'value100');

INSERT INTO powerdns.domains (name, master, type) VALUES ('name1', 'master1', 'type1');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name2', 'master2', 'type2');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name3', 'master3', 'type3');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name4', 'master4', 'type4');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name5', 'master5', 'type5');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name6', 'master6', 'type6');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name7', 'master7', 'type7');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name8', 'master8', 'type8');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name9', 'master9', 'type9');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name10', 'master10', 'type10');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name11', 'master11', 'type11');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name12', 'master12', 'type12');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name13', 'master13', 'type13');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name14', 'master14', 'type14');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name15', 'master15', 'type15');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name16', 'master16', 'type16');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name17', 'master17', 'type17');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name18', 'master18', 'type18');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name19', 'master19', 'type19');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name20', 'master20', 'type20');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name21', 'master21', 'type21');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name22', 'master22', 'type22');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name23', 'master23', 'type23');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name24', 'master24', 'type24');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name25', 'master25', 'type25');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name26', 'master26', 'type26');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name27', 'master27', 'type27');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name28', 'master28', 'type28');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name29', 'master29', 'type29');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name30', 'master30', 'type30');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name31', 'master31', 'type31');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name32', 'master32', 'type32');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name33', 'master33', 'type33');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name34', 'master34', 'type34');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name35', 'master35', 'type35');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name36', 'master36', 'type36');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name37', 'master37', 'type37');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name38', 'master38', 'type38');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name39', 'master39', 'type39');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name40', 'master40', 'type40');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name41', 'master41', 'type41');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name42', 'master42', 'type42');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name43', 'master43', 'type43');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name44', 'master44', 'type44');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name45', 'master45', 'type45');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name46', 'master46', 'type46');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name47', 'master47', 'type47');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name48', 'master48', 'type48');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name49', 'master49', 'type49');
INSERT INTO powerdns.domains (name, master, type) VALUES ('name50', 'master50', 'type50');

