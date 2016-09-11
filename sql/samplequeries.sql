create table apis_designs (   id int(11) not null auto_increment primary key, 
                              permission_id int(11), 
                              user_id int(11), 
                              design varchar(150) not null, 
                              
                              foreign key fk_permission_id(permission_id) references apis_permissions(id), 
                              foreign key fk_user_id(user_id) references apis_users(id)
                            );
                            
insert into apis_designs (permission_id, design) values (1, 'test.yml');
insert into apis_designs (user_id, design) values (1, 'test1.yml');


 select * from apis_users u, apis_permissions p, apis_user_permission_matches m where u.id = m.user_id and p.id = m.permission_id and u.id = 2 \G
 
 select u.id, u.user_name, p.name, d.user_id touser, d.permission_id togroup, d.design 
       from apis_users u, apis_permissions p, apis_user_permission_matches m, apis_designs d 
       where u.id = m.user_id and p.id = m.permission_id and u.id = 1 
             and 
             ( 
             (d.permission_id is not null and d.permission_id = p.id)
             or
             (d.user_id is not null and d.user_id = u.id) 
             );
             
             
 select u.id, u.user_name, d.design 
       from apis_users u, apis_designs d 
       where d.user_id is not null and d.user_id = u.id;
       
 select u.id, u.user_name, p.name 'group', d.design 
       from apis_users u, apis_permissions p, apis_user_permission_matches m, apis_designs d 
       where u.id = m.user_id and p.id = m.permission_id and u.id = 1 
             and  d.permission_id is not null and d.permission_id = p.id 
       order by 'group';
       
select p.id from apis_users u, apis_permissions p, apis_user_permission_matches m where u.id = m.user_id and p.id = m.permission_id and p.name = 'New Member' and u.id = 1;
       
