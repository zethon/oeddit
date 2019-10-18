<?php

	/**
		Soc functions
    */
    function get_society_id($sname)
    {
        $retval = ($rows = query("SELECT * FROM societies WHERE soc_name = ?", $sname));
        if ($retval !== false)
        {
            return $rows[0];
        }
        
        return false;
    }

function get_society($sname, $show_deleted = false)
{
    return  (($s = query("
SELECT 	s.*, 
d.rev_id, 
d.revised_by, 
d.info, 
DATE_FORMAT(d.time, '%H:%i, %b %d, %Y') \"time\",
r.username revised_by,
c.username c_name
FROM societies s 
JOIN users c ON c.user_id = s.created_by
LEFT JOIN soc_details d ON s.soc_id = d.soc_id
JOIN users r ON r.user_id = d.revised_by
WHERE s.soc_name =  ?",
                            $sname
                        )
            )
            && ($show_deleted || $s[0]["status"] != "DELETED")
            )
            ? $s[0]:false;
}

	function get_society_by_id($sid, $show_deleted = false)
	{
		return  (($s = query("	SELECT 	s.*, 
										d.time, d.info, d.username r_name,
										c.username c_name
								FROM societies s 
								JOIN users c on c.user_id = s.created_by
								LEFT JOIN 
                                	(SELECT * 
                                     FROM soc_details
                                     JOIN users r on r.user_id = revised_by
                                    ) d                                  
                                on s.soc_id = d.soc_id
								WHERE s.soc_id = ?",
								$sid
					  		)
				)
				&& ($show_deleted || $s[0]["status"] != "DELETED")
				)
				? $s[0]:false;
	}

	function soc_rel($soc, $uid = null)
	{
		isset($uid) || $uid = $_SESSION["user"]["user_id"];
		$status = query("SELECT EXISTS(SELECT 1 FROM soc_subs WHERE user_id=? and soc_id = ?) as \"sub\",
								EXISTS(SELECT 1 FROM soc_mods WHERE user_id=? and soc_id = ?) as \"mod\", 
								EXISTS(SELECT 1 FROM soc_bans WHERE user_id=? and soc_id = ?) as \"banned\",
								EXISTS(SELECT 1 FROM users    WHERE user_id=? and status = ?) as \"admin\"",
						$uid, $soc["soc_id"], 
						$uid, $soc["soc_id"], 
						$uid, $soc["soc_id"], 
						$uid, "ADMIN"
						)[0];
		$status["creator"] = $soc["created_by"] == $uid;
		return $status;
	}

	function get_subbed_socs()
	{
		return query("	SELECT 	ss.soc_id, ss.user_id, 
								time as \"subbed since\", 
								s.soc_name as \"society\" 
						FROM soc_subs ss
						JOIN societies s on s.soc_id=ss.soc_id
						WHERE ss.user_id = ?
						ORDER BY time desc",
						$_SESSION["user"]["user_id"]
					);
	}

	function get_modded_socs()
	{
		return query("	SELECT 	ss.soc_id, ss.user_id, 
								time as \"mod since\", 
								s.soc_name as \"society\" 
						FROM soc_mods ss
						JOIN societies s on s.soc_id=ss.soc_id
						WHERE ss.user_id = ?
						ORDER BY time desc",
						$_SESSION["user"]["user_id"]
					);
	}
	// end
	
	/**
		Post functions
	*/
	function get_posts($soc, $offset = 0, $lim = 20)
	{
		return query("	SELECT  p.*,
								u.username, 
								if(p.status='STICKIED',10,0) as \"rank\",
								sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) as \"votes\",
								(SELECT count(*) 
								 FROM post_views w
								 WHERE w.post_id = p.post_id
								) as \"views\",
								(SELECT count(*) 
								   FROM comments c
								  WHERE c.post_id = p.post_id
								) as \"comments\",
								(SELECT pv.vote 
								   FROM post_votes pv
								  WHERE pv.post_id = p.post_id
								    AND pv.user_id = ?
								) as \"vote\"
						FROM posts p 
						JOIN societies s on p.soc_id = s.soc_id 
						LEFT JOIN post_votes v on p.post_id = v.post_id 
						JOIN users u on p.user_id = u.user_id
						
						WHERE p.soc_id = ? AND p.status != 'DELETED'
						GROUP BY p.post_id
						ORDER BY rank DESC, votes DESC, views DESC
						LIMIT ?
						OFFSET ?",

						$_SESSION["user"]["user_id"],
						$soc["soc_id"], 
						$lim, 
						$offset
					);
	}
	
	function get_post($pid)
	{
		return ($p = query("	SELECT  p.*, 
										u.username,
										sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) as votes,
										(SELECT count(*) 
										 FROM post_views w
										 WHERE w.post_id = p.post_id
										) as \"views\",
										(SELECT count(*) 
										 FROM comments c
										 WHERE c.post_id = p.post_id
										) as \"comments\",
										(SELECT pv.vote 
										   FROM post_votes pv
										  WHERE pv.post_id = p.post_id
										    AND pv.user_id = ?
										) as \"vote\"
								FROM posts p 
								LEFT JOIN post_votes v on v.post_id = p.post_id
								JOIN users u on p.user_id = u.user_id
								
								WHERE p.post_id = ?
								GROUP BY p.post_id ",

								$_SESSION["user"]["user_id"],
								$pid
							)
				)
				?
				$p[0]:false;		
	}

	function post_rel($pid, $uid = null)
	{
		isset($uid) || $uid = $_SESSION["user"]["user_id"];
		return ($r = query("	SELECT 
									exists(SELECT 1 FROM post_subs WHERE user_id=? and post_id = ?) as \"sub\",
									vote
								FROM post_votes
								WHERE user_id = ? and post_id = ?",
								$uid, $pid,
								$uid, $pid
						)
				)
				?
				$r[0]:false;
	}

	function get_post_hist($u = null)
	{
		isset($u) || $u = $_SESSION["user"]["user_id"];

		return query("	SELECT 	p.*,
								s.soc_id,
								s.soc_name as \"society\",
								(SELECT sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) 
								   FROM post_votes v
								  WHERE v.post_id = p.post_id
								) as \"votes\"
						FROM posts p
						JOIN societies s on s.soc_id=p.soc_id
						WHERE p.user_id = ?
						ORDER BY time desc",
						$u["user_id"]
					);
	}

	// end
	
	/**
		Comment functions
	*/
	function get_comments($post)
	{ 
		return query("	SELECT 	if(c.parent_id is NULL, c.comm_id, c.parent_id) anc_id, 
								c.comm_id, c.time, c.text, c.status,
								u.user_id, u.username,
								sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) as votes,
								(SELECT cv.vote 
								   FROM comm_votes cv
								  WHERE cv.comm_id = c.comm_id
								    AND cv.user_id = ?
								) as \"vote\"
						FROM comments c 
						LEFT JOIN comm_votes v on v.comm_id=c.comm_id
						JOIN users u on c.user_id=u.user_id
						WHERE post_id = ?
						GROUP BY c.comm_id
						ORDER BY votes DESC
						LIMIT 100",

						$_SESSION["user"]["user_id"],
						$post["post_id"]
					);
	}

	function get_comment($cid)
	{
		return ($r = query("	SELECT 	if(c.parent_id is NULL, c.comm_id, c.parent_id) anc_id, 
										c.comm_id, c.time, c.text, c.status,
										u.user_id, u.username,
										sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) as votes,
										(SELECT cv.vote 
										   FROM comm_votes cv
										  WHERE cv.comm_id = c.comm_id
										    AND cv.user_id = ?
										) as \"vote\"
								FROM comments c 
								LEFT JOIN comm_votes v on v.comm_id=c.comm_id
								JOIN users u on c.user_id=u.user_id
								WHERE c.comm_id = ?",
								
								$_SESSION["user"]["user_id"],
								$cid
						)
				)
				?
				$r[0]:false;
	}

	function comm_rel($cid, $uid = null)
	{
		isset($uid) || $uid = $_SESSION["user"]["user_id"];
		return ($r = query("SELECT vote
							  FROM comm_votes
							 WHERE user_id = ? and comm_id = ?",							
							$uid, $cid,
							$uid, $cid
						)
				)
				?
				$r[0]:false;
	}

	function get_comm_hist($u = null)
	{
		isset($u) || $u = $_SESSION["user"]["user_id"];

		return query("	SELECT 	s.soc_id,
								s.soc_name as \"society\",
								c.*,
								p.post_id,
								p.title as \"post\",
								(SELECT sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) 
								   FROM comm_votes v
								  WHERE v.comm_id = c.comm_id
								) as \"votes\"
						FROM comments c
						JOIN posts p on p.post_id=c.post_id 
						JOIN societies s on s.soc_id=p.soc_id
						WHERE c.user_id = ?
						ORDER BY time desc",
						$u["user_id"]
					);
	}
	// end
	
	/**
		Mod functions
	*/
	function has_mod_access($soc, $uid = null)
	{
		isset($uid) || $uid = $_SESSION["user"]["user_id"];
		$status = soc_rel($soc, $uid);
		return ($status["mod"] || $status["admin"]);
	}
	
	function get_soc_bans($soc)
	{
		return query("	SELECT b.soc_id, u.user_id, u.username, m.user_id mod_id, m.username \"banned by\", 
								l.time, l.comment reason
						FROM soc_bans b
						JOIN (SELECT user_id, mod_id, time, comment
							  FROM user_control_mod_log 
							  WHERE action = 'BAN'
                              and soc_id= ?
							  GROUP BY user_id
							  HAVING time = max(time)
							 ) as l on l.user_id = b.user_id
						JOIN users as u
							  on u.user_id = b.user_id
						JOIN users as m
							  on m.user_id = l.mod_id",
						$soc["soc_id"]
					);
	}

	function get_mod_list($soc)
	{
		return query("	SELECT 	u.user_id mod_id, u.username \"mod name\", 
								m.user_id modder_id, m.username \"promoted by\", 
								l.time, l.comment reason
						FROM soc_mods b
						JOIN (SELECT user_id, mod_id, time, comment
							  FROM user_control_mod_log 
							  WHERE action = 'MOD'
                              and soc_id= ?
							  GROUP BY user_id
							  HAVING time = max(time)
							 ) as l on l.user_id = b.user_id
						JOIN users as u
							  on u.user_id = b.user_id
						JOIN users as m
							  on m.user_id = l.mod_id",
						$soc["soc_id"]
					);
	}
	
	function get_del_posts($soc)
	{
		return query("SELECT    p.post_id, 
								p.title \"title\", 
								p.text \"text\", 
								s.soc_name society,
								u.username \"deleted by\", 
								l.time, l.comment 
						FROM posts p
						JOIN post_control_log l	on l.post_id = p.post_id
						JOIN users u on u.user_id = l.mod_id
						JOIN societies s on s.soc_id = p.soc_id
						WHERE p.status = 'DELETED' 
						  and l.action = 'DELETE' 
						  and p.soc_id = ?
						  and l.time = (SELECT max(l2.time)
						  				FROM post_control_log l2
						  				WHERE l2.post_id = p.post_id
										  and l2.action = 'DELETE' 
						  				)
						ORDER BY l.time desc",
						$soc["soc_id"]
					);
	}
	
	function get_del_comms($soc)
	{
		return query("SELECT    c.comm_id, 
								c.text \"comment text\", 
								u.username \"deleted by\", 
								l.time, l.comment 
						FROM comments c
						JOIN comm_control_log l	on l.comm_id = c.comm_id
						JOIN posts p on p.post_id = c.post_id
						JOIN users u on u.user_id = l.mod_id
						WHERE c.status = 'DELETED'
						  and l.action = 'DELETE'
						  and p.soc_id = ?
						  and l.time = (SELECT max(l2.time)
						  				FROM comm_control_log l2
						  				WHERE l2.comm_id = c.comm_id
										  and l2.action = 'DELETE' 
						  				)
						ORDER BY l.time desc",
						$soc["soc_id"]
					);
	}
	
	function get_mod_log($soc)
	{
		return ["user" => get_mod_usr_log($soc), 
				"post" => get_mod_post_log($soc),
				"comm" => get_mod_comm_log($soc)];
	}
	
	function get_mod_usr_log($soc)
	{
		return query("	SELECT  l.*, u.username, 
								m.mod_name \"mod name\"
						FROM user_control_mod_log l
						JOIN (SELECT user_id as mod_id, username as mod_name FROM users) m on l.mod_id=m.mod_id
						JOIN (SELECT user_id, username FROM users) u on l.user_id = u.user_id
						WHERE soc_id=?
						ORDER BY l.time DESC",
						$soc["soc_id"]
					);
	}
	
	function get_mod_post_log($soc)
	{
		return query("	SELECT  l.*, substr(p.title,1,100) as \"title\", 
								m.mod_name \"mod name\",
								s.soc_name society
						FROM post_control_log l
						JOIN (SELECT user_id as mod_id, username as mod_name FROM users) m on l.mod_id = m.mod_id
						JOIN (SELECT post_id, soc_id, title FROM posts) p on l.post_id = p.post_id
						JOIN societies s on s.soc_id = p.soc_id
						WHERE p.soc_id = ?
						ORDER BY l.time DESC",
						$soc["soc_id"]
					);
	}
	
	function get_mod_comm_log($soc)
	{
		return query("	SELECT  l.*, substr(c.text,1,100) as \"text\", 
								m.mod_name \"mod name\"
						FROM comm_control_log l
						JOIN (SELECT user_id as mod_id, username as mod_name FROM users) m on l.mod_id=m.mod_id
						JOIN (SELECT comm_id, post_id, text FROM comments) c on l.comm_id=c.comm_id
						JOIN (SELECT post_id, soc_id FROM posts) p on c.post_id=p.post_id
						WHERE soc_id=?
						ORDER BY l.time DESC",
						$soc["soc_id"]
					);
	}
	// end
	
	/**
		Admin functions
	*/
	function get_site_bans()
	{
		return query("	SELECT u.user_id, u.username, a.admin_id, a.admin_name \"banned by\", l.time, l.comment \"reason\"
						FROM users u
						JOIN (SELECT user_id, admin_id, max(time) as time, comment
							  FROM user_control_admin_log 
							  WHERE action = 'BAN'
							  GROUP BY user_id) as l
							  on l.user_id = u.user_id
						JOIN (SELECT user_id as admin_id, username as admin_name
							  FROM users) as a
							  on a.admin_id = l.admin_id
						WHERE u.status = 'BANNED'
					");
	}
	
	function get_locked_socs()
	{
		return query("	SELECT 	s.soc_id, s.soc_name \"society\", 
								a.admin_id, a.admin_name \"admin name\", 
								l.time, l.comment
						FROM societies s
						JOIN (SELECT soc_id, admin_id, max(time) as time, comment
							  FROM soc_control_admin_log 
							  WHERE action = 'LOCK'
							  GROUP BY soc_id) as l
							  on l.soc_id = s.soc_id
						JOIN (SELECT user_id as admin_id, username as admin_name
							  FROM users) as a
							  on a.admin_id = l.admin_id
						WHERE s.status = 'LOCKED'
					");
	}
	
	function get_admin_list()
	{
		return query("	SELECT 	u.user_id, u.username \"admin name\",
								a.user_id, a.username \"promoted by\", 
								l.time, l.comment reason
						FROM users u
						JOIN (SELECT user_id, admin_id, time, comment
							  FROM user_control_admin_log
							  WHERE action = 'ADMIN'
							  GROUP BY user_id
							  HAVING time = max(time)
							  ) as l
							  on l.user_id = u.user_id
						JOIN users as a
							  on a.user_id = l.admin_id
						WHERE u.status = 'ADMIN'
					");
	}
	
	function get_admin_log()
	{
		return ["user" => get_admin_usr_log(), 
				"soc" => get_admin_soc_log()];
	}	
	
	function get_admin_soc_log()
	{
		return query("	SELECT  l.*, s.soc_name \"society\", 
								a.admin_name \"admin name\"
						FROM soc_control_admin_log l
						JOIN (SELECT user_id as admin_id, username as admin_name FROM users) a on l.admin_id=a.admin_id
						JOIN societies s on l.soc_id=s.soc_id
						ORDER BY l.time desc
					");
	}
	
	function get_admin_usr_log()
	{
		return query("	SELECT l.*, u.username, a.admin_name \"admin name\"
						FROM user_control_admin_log l
						JOIN (SELECT user_id as admin_id, username as admin_name FROM users) a on l.admin_id=a.admin_id
						JOIN (SELECT user_id, username FROM users) u on l.user_id=u.user_id
						ORDER BY l.time desc
					");
	}

	function get_user_reports()
	{
		return query("	SELECT  r.*, 
								ru.username \"user\",
								rr.username \"reported by\"
						FROM user_reports ur
						JOIN reports r on r.report_id = ur.report_id
						JOIN users ru on ru.user_id = ur.user_id
						JOIN users rr on rr.user_id = r.user_id
						ORDER BY r.time DESC");
	}

	function get_soc_reports()
	{
		return query("	SELECT  r.*, 
								rs.soc_name \"society\",
								rr.username \"reported by\"
						FROM soc_reports sr
						JOIN reports r on r.report_id = sr.report_id
						JOIN societies rs on rs.soc_id = sr.soc_id
						JOIN users rr on rr.user_id = r.user_id
						ORDER BY r.time DESC");
	}

	function get_post_reports($sid)
	{
		return query("	SELECT  r.*, s.soc_name \"society\",
								rp.post_id,
								rp.title \"title\",
								rp.text \"text\",
								rr.username \"reported by\"
						FROM post_reports pr
						JOIN reports r on r.report_id = pr.report_id
						JOIN posts rp on rp.post_id = pr.post_id
						JOIN users rr on rr.user_id = r.user_id
						JOIN societies s on s.soc_id = rp.soc_id
						WHERE rp.soc_id = ?
						ORDER BY r.time DESC",
						$sid
					);
	}

	function get_comm_reports($sid)
	{
		return query("	SELECT  r.*, 
								rc.comm_id,
								rc.text \"comment text\",
								rr.username \"reported by\"
						FROM comm_reports cr
						JOIN reports r on r.report_id = cr.report_id
						JOIN comments rc on rc.comm_id = cr.comm_id
						JOIN posts p on p.post_id = rc.post_id
						JOIN users rr on rr.user_id = r.user_id
						WHERE p.soc_id = ?
						ORDER BY r.time DESC",
						$sid
					);
	}
	// end
	
	/**
		Misc.
	*/
	
	function get_user($uname, $show_deleted = false)
	{
		return  (($u = query("SELECT * FROM users WHERE username = ?", $uname)) && ($show_deleted || !is_deleted($u[0])))
				?
				$u[0]:false;
	}
	
	function get_user_by_id($uid, $show_deleted = false)
	{
		return  (($u = query("SELECT * FROM users WHERE user_id = ?", $uid)) && ($show_deleted || !is_deleted($u[0])))
				? 
				$u[0]:false;
	}

	function get_inbox()
	{
		return query("SELECT p.*, 
							 s.username \"sender\"-- ,
							 -- if(p.read_time is NULL, 'UNREAD', 'READ') \"status\"
						FROM pms p
						JOIN users s on s.user_id = p.sender
						WHERE p.receiver = ?
						ORDER BY p.time DESC",
						$_SESSION["user"]["user_id"]
					);
	}

	function get_outbox()
	{
		return query("SELECT p.*, 
							 r.username \"receiver\"
						FROM pms p
						JOIN users r on r.user_id = p.receiver
						WHERE p.sender = ?
						ORDER BY p.time DESC",
						$_SESSION["user"]["user_id"]
					);
	}

	function get_active_users($t = 10)
	{
		return ($r = query("	SELECT count(DISTINCT user_id) \"active\"
								FROM `user_activity` 
								WHERE TIMESTAMPDIFF(MINUTE, time, sysdate()) < ?",
								$t
							)
				)
				?
				$r[0]["active"]:0;
	}

	function get_active_admins($t = 10)
	{
		return ($r = query("	SELECT count(DISTINCT a.user_id) \"active\"
						FROM user_activity a
						JOIN users u on u.user_id = a.user_id
						WHERE u.status = 'ADMIN'
						and TIMESTAMPDIFF(MINUTE, a.time, sysdate()) < ?",
						$t
					)
				)
				?
				$r[0]["active"]:0;
	}

	function get_new_regs_today()
	{
		return ($r = query("	SELECT  count(*) \"regs\"
								FROM users
			                    WHERE datediff(sysdate(), join_date) = 0"
        			        )
				)
				?
				$r[0]["regs"]:0;
	}

	function get_most_active_socs($n = 10)
	{
		return query("	SELECT 	count(*) \"#comments\",
								s.soc_name society
						FROM societies s
						JOIN posts p on p.soc_id = s.soc_id
						JOIN comments c on c.post_id = p.post_id
	                    WHERE datediff(sysdate(), c.time) = 0
	                    GROUP BY s.soc_id
	                    ORDER BY \"#comments\" DESC
	                    LIMIT ?",
	                    $n
			       );
	}

	function get_fastest_growing_socs($n = 10)
	{
		return query("	SELECT 	count(*) \"#subs\",
								s.soc_name \"society\"
						FROM societies s
						JOIN soc_subs ss on ss.soc_id = s.soc_id
	                    WHERE datediff(sysdate(), ss.time) = 0
	                    GROUP BY s.soc_id
	                    ORDER BY \"#subs\"
	                    LIMIT ?",
	                    $n
			        );
	}

	function get_comms_today($sid)
	{
		return ($r = query("	SELECT 	count(*) \"#comments\"
								FROM societies s
								JOIN posts p on p.soc_id = s.soc_id
								JOIN comments c on c.post_id = p.post_id
			                    WHERE datediff(sysdate(), c.time) = 0
			                    and s.soc_id = ?",
			                    $sid
						       	)
				)
				?
				$r[0]["#comments"]:0;
	}

	function get_posts_today($sid)
	{
		return ($r = query("	SELECT 	count(*) \"#posts\"
								FROM societies s
								JOIN posts p on p.soc_id = s.soc_id
			                    WHERE datediff(sysdate(), p.time) = 0
			                    and s.soc_id = ?",
			                    $sid
			       )
				)
				?
				$r[0]["#posts"]:0;
	}

	function get_new_subs_today($sid)
	{
		return ($r = query("	SELECT  count(*) \"subs\"
								FROM soc_subs ss
			                    WHERE datediff(sysdate(), ss.time) = 0 "
        			        )
				)
				?
				$r[0]["subs"]:0;
	}

	function get_active_users_soc($sid, $t = 10)
	{
		return ($r = query("	SELECT count(DISTINCT w.user_id) \"active\"
								FROM soc_views w
								WHERE TIMESTAMPDIFF(MINUTE, w.time, sysdate()) < ?
			                    and w.soc_id = ?",
								$t,
								$sid
							)
				)
				?
				$r[0]["active"]:0;
	}	

	function get_active_mods($sid, $t = 10)
	{
		return ($r = query("	SELECT count(DISTINCT w.user_id) \"active\"
								FROM soc_views w
								JOIN soc_mods m on m.user_id = w.user_id
								WHERE TIMESTAMPDIFF(MINUTE, w.time, sysdate()) < ?
			                    and w.soc_id = ?",
								$t,
								$sid
							)
				)
				?
				$r[0]["active"]:0;
	}

	function get_new_regs_trend($days = 31)
	{
		return query("	SELECT  count(*) regs,
								datediff(sysdate(), join_date) \"day\"
						FROM users
	                    WHERE datediff(sysdate(), join_date) < ?
						GROUP BY datediff(sysdate(), join_date)
						ORDER BY day",
	                    $days
	                );
	}

	function get_active_trend($hours = 24)
	{
		return query("	SELECT  count(DISTINCT a.user_id) \"active\",
								TIMESTAMPDIFF(HOUR, a.time, sysdate()) \"hour\"
						FROM user_activity a
	                    WHERE TIMESTAMPDIFF(HOUR, a.time, sysdate()) < ?
						GROUP BY TIMESTAMPDIFF(HOUR, a.time, sysdate())
						ORDER BY \"hour\"",
	                    $hours
	                );
	}

	function get_active_admin_trend($hours = 24)
	{
		return query("	SELECT  count(DISTINCT a.user_id) \"active\",
								TIMESTAMPDIFF(HOUR, a.time, sysdate()) \"hour\"
						FROM user_activity a
						JOIN users u on u.user_id = a.user_id
	                    WHERE TIMESTAMPDIFF(HOUR, a.time, sysdate()) < ?
	                    and u.status = 'ADMIN'
						GROUP BY TIMESTAMPDIFF(HOUR, a.time, sysdate())
						ORDER BY \"hour\"",
	                    $hours
	                );
	}

	function get_active_trend_soc($sid, $hours = 24)
	{
		return query("	SELECT  count(DISTINCT w.user_id) \"active\",
								TIMESTAMPDIFF(HOUR, w.time, sysdate()) \"hour\"
						FROM soc_views w
	                    WHERE TIMESTAMPDIFF(HOUR, w.time, sysdate()) < ?
	                    and w.soc_id = ?
						GROUP BY TIMESTAMPDIFF(HOUR, w.time, sysdate())
						ORDER BY \"hour\"",
	                    $hours,
	                    $sid
	                );
	}

	function get_subs_trend($sid, $days = 7)
	{
		return query("	SELECT  count(DISTINCT ss.user_id) \"subs\",
								TIMESTAMPDIFF(DAY, ss.time, sysdate()) \"day\"
						FROM soc_subs ss
	                    WHERE TIMESTAMPDIFF(DAY, ss.time, sysdate()) < ?
	                    and ss.soc_id = ?
						GROUP BY TIMESTAMPDIFF(DAY, ss.time, sysdate())
						ORDER BY \"day\"",
	                    $days,
	                    $sid
	                );
	}

	function get_comms_trend($sid, $days = 7)
	{
		return query("	SELECT  count(*) \"#comments\",
								TIMESTAMPDIFF(DAY, c.time, sysdate()) \"day\"
						FROM comments c
						JOIN posts p on p.post_id = c.post_id
	                    WHERE TIMESTAMPDIFF(DAY, c.time, sysdate()) < ?
	                    and p.soc_id = ?
						GROUP BY TIMESTAMPDIFF(DAY, c.time, sysdate())
						ORDER BY \"day\"",
	                    $days,
	                    $sid
	                );
	}

	function get_posts_trend($sid, $days = 7)
	{
		return query("	SELECT  count(DISTINCT p.user_id) \"#posts\",
								TIMESTAMPDIFF(DAY, p.time, sysdate()) \"day\"
						FROM posts p
	                    WHERE TIMESTAMPDIFF(DAY, p.time, sysdate()) < ?
	                    and p.soc_id = ?
						GROUP BY TIMESTAMPDIFF(DAY, p.time, sysdate())
						ORDER BY \"day\"",
	                    $days,
	                    $sid
	                );
	}

	function get_user_score($uid = null)
	{
		isset($uid) || $uid = $_SESSION["user"]["user_id"];
		return ($r = query("	SELECT (SELECT sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0)))
										FROM comments c 
										LEFT JOIN comm_votes v on v.comm_id = c.comm_id
				                 	   and c.user_id = ?
					                  ) \"cscore\",
										(SELECT	sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0)))
										FROM posts p 
										LEFT JOIN post_votes v on v.post_id = p.post_id
					                    and p.user_id = ?
					                  ) \"pscore\"
								FROM dual",
								$uid,
								$uid
						    )
				)
				?
				$r[0]:false;
	}

	function get_news_feed($lim = 100, $offset = 0)
	{
		return query("	SELECT  p.*,
								u.username, 
								s.soc_name \"society\",
								sum(if(v.vote='UP',1,if(v.vote='DOWN',-1,0))) as votes,
								(SELECT count(*)
								 FROM post_views w
								 WHERE w.post_id = p.post_id
								) as \"views\",
								(SELECT count(*)
								   FROM comments c
								  WHERE c.post_id = p.post_id
								) as \"comments\",
								(SELECT pv.vote 
								   FROM post_votes pv
								  WHERE pv.post_id = p.post_id
								    AND pv.user_id = ?
								) as \"vote\"
						FROM posts p 
						JOIN (SELECT soc_id FROM soc_subs WHERE user_id = ?) ss on p.soc_id = ss.soc_id
						JOIN (SELECT soc_id, soc_name FROM societies) s on s.soc_id = ss.soc_id
						LEFT JOIN post_votes v on p.post_id = v.post_id
						JOIN users u on p.user_id = u.user_id
                        AND p.status != 'DELETED'
						
						GROUP BY p.post_id
						ORDER BY votes DESC, comments DESC, views DESC
						LIMIT ?
						OFFSET ?",

						$_SESSION["user"]["user_id"],
						$_SESSION["user"]["user_id"],
						$lim, 
						$offset
					);
	}

	function get_soc_edit_history($sid, $lim = 20, $offset = 0)
	{
		return query(" SELECT d.rev_id, d.revised_by, d.info, DATE_FORMAT(d.time, '%H:%i, %b %d, %Y') \"time\", u.username
						 FROM soc_details d
						 JOIN users u on u.user_id = d.revised_by
						WHERE d.soc_id = ?
						ORDER BY d.time DESC
						LIMIT ?
					   OFFSET ?",
						$sid,
						$lim,
						$offset
					);
	}

	function get_soc_info_revision($rid)
	{
		return ($r = query(" SELECT d.*, u.username
								 FROM soc_details d
								 JOIN users u on u.user_id = d.revised_by
								WHERE d.rev_id = ?",
								$rid)
				)
				?
				$r[0]:false;
	}
?>