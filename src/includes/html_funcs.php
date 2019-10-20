<?php

	/**
		The printer
	*/
	function to_html($a)
	{
		$html = "<".$a["tag"];
		// opening tag
		foreach($a["attribs"] as $att => $val)
		{			
			$html .= " ".$att."=\"".$val."\" ";
		}
		$html .= ">";

		if (isset($a["children"]))
		{
			foreach($a["children"] as $child)
			{
				$html .= "\n".to_html($child)."\n";
			}
		}
		elseif (isset($a["data"]))
		{
			$html .= $a["data"];
		}

		// closing tag
		$html .= "</".$a["tag"].">";

		return $html;
	}

	/**
		HTML tags
	*/

	function make_tag($t, $class = "", $id = "")
	{
		return ["tag" => $t, "attribs" => ["class" => $class, "id" => $id, "style" => ""]];
	}

	function make_table($rows, $hds, $class = "", $id = "", $ucols = [], $scols = [], $pcols = [])
	{
		// isset($hds) || ($hds = array_keys($rows[0]));
		$t = make_tag("table", $class, $id);

		$thead = make_tag("thead");
		$thead["children"][] = make_tag("tr");
		// table headers
		foreach($hds as $th)
		{			
			$c = make_tag("th");
			$c["data"] = $th;
			$thead["children"][0]["children"][] = $c;
		}

		$tbody = make_tag("tbody");
		foreach ($rows as $row) 
		{
			$r = make_tag("tr");
			for ($i = 0; $i < count($hds); $i++) 
			{
				$col = $hds[$i];
				$c = make_tag("td");
				if (in_array($i, $ucols))
					$c["children"][] = user_link($row[$col]);
				else if (in_array($i, $scols))
					$c["children"][] = soc_link($row[$col]);
				else if (in_array($i, $pcols))
					$c["children"][] = post_link($row["post_id"], $row[$col], $row["society"]);
				else
					$c["children"][] = span($row[$col]);
				$r["children"][] = $c;
			}
			$tbody["children"][] = $r;
		}

		$t["children"][] = $thead;
		$t["children"][] = $tbody;

		return $t;
	}

	function make_form($action, $method, $class = "")
	{
		$f = make_tag("form", $class);
		$f["attribs"]["action"] = $action;
		$f["attribs"]["method"] = $method;
		
		return $f;
	}

	function add_field($f, $id, $label = "", $req = false, $class = "", $type = "text")
	{
		$in = make_tag("input", $class);
		$in["attribs"]["type"] = $type;
		$in["attribs"]["id"] = $id;
		$in["attribs"]["name"] = $id;
		$in["attribs"]["placeholder"] = $label;
		!$req || $in["attribs"]["required"] = "";
		$f["children"][] = label($id, $label);
		$f["children"][] = $in;
		return $f;
	}

	function label($id, $label)
	{
		$tmp = make_tag("label");
		$tmp["attribs"]["class"] .= "control-label sr-only";
		$tmp["attribs"]["for"] = $id;
		$tmp["data"] = $label;
		return $tmp;
	}

	function add_button($f, $text, $class = "btn", $type = "submit")
	{
		$b = make_tag("button", $class);
		$b["attribs"]["type"] = $type;
		$b["data"] = $text;
		$f["children"][] = $b;
		return $f;
	}

	function div($elem = null, $class = "")
	{
		$d = make_tag("div", $class);
		$d["children"][] = $elem;

		return $d;
	}

	function par($data, $class = "", $id = "")
	{
		$tmp = make_tag("p", $class, $id);
		$tmp["data"] = $data;
		return $tmp;
	}

	function hr()
	{
		return make_tag("hr");
	}

	function br()
	{
		return make_tag("br");
	}

	function small($text, $class = "", $id = "")
	{
		$t = make_tag("small", $class, $id);
		$t["data"] = $text;
		return $t;
	}

	function strong($text, $class = "", $id = "")
	{
		$t = make_tag("strong", $class, $id);
		$t["data"] = $text;
		return $t;
	}

	function span($data = "", $class = "", $id = "")
	{
		$span = make_tag("span", $class, $id);
		$span["data"] = $data;
		return $span;
	}

	function h($n = 1, $text = "", $class = "", $id = "")
	{
		$h = make_tag("h".$n, $class, $id);
		$h["data"] = $text;
		return $h;
	}

	function a($url, $class = "", $id = "")
	{
		$a = make_tag("a", $class, $id);
		$a["attribs"]["href"] = $url;
		return $a;
	}

	function css_float($tag, $dir)
	{
		$tag["attribs"]["style"] .= "float:".$dir.";";
		return $tag;
	}

	/**
		Post-related
	*/
	function post_full($p, $s, $mod)
	{
		// post title
		$a = a("post.php?pid=".$p["post_id"]."&soc=".$s["soc_name"], "col-md-11");
		$title = div(h(4, $p["title"]."\t(".(($p["votes"]>0) ? "+":"").$p["votes"].")", " post-title"));
		if ($p["status"]=="STICKIED")
			$title["children"][] = css_float(div(glyph("pushpin")), "right");
		$d = small("submitted by ".$p["username"]." on ".$p["time"], "post-details");
		$a["children"][] = $title;
		$a["children"][] = $d;

		// post text
		$text = par($p["text"], "post-text");

		// vote buttons
		$vb = post_vote_buttons($p);
		$vb["attribs"]["class"] .= " col-md-1";

		// report button
		$rept = a("", "btn btn-xs btn-link post-report");
		$rept["data"] = "report";
		$rept["attribs"]["data-toggle"] = "modal";
		$rept["attribs"]["data-target"] = "#report-post";
		$rept["attribs"]["value"] = $p["post_id"];
		$rept["attribs"]["style"] = "float:right;";

		// delete button
		$del = a("", "btn btn-xs btn-link post-del");
		$del["children"][] = glyph("trash");
		$del["children"][] = span("delete");
		$del["attribs"]["data-toggle"] = "modal";
		$del["attribs"]["data-target"] = "#del-post";
		$del["attribs"]["value"] = $p["post_id"];
		$del["attribs"]["style"] = "float:right;";

		// sticky button
		$sticky = a("", "btn btn-xs btn-link post-sticky");
		$sticky["children"][] = glyph("pushpin");
		$sticky["children"][] = span($p["status"]=="STICKIED" ? "unsticky":"sticky");
		$sticky["attribs"]["data-toggle"] = "modal";
		$sticky["attribs"]["data-target"] = "#sticky-post";
		$sticky["attribs"]["value"] = $p["post_id"];
		$sticky["attribs"]["style"] = "float:right;";

		// put it all together
		$h = div($vb, "row");
		$h["children"][] = $a;
		$h = div($h, "panel-heading");
		$t = div($text, "panel-body well");
		$t["children"][] = hr();
		if ($mod)
		{
			$t["children"][] = $del;
			$t["children"][] = $sticky;
		}
		else
		{
			$t["children"][] = $rept;	
		}
		
		$final = div($h, "panel panel-default well");
		$final["children"][] = $t;
		return $final;
	}

	function post_summary($p, $sname, $show_soc = false)
	{
		$a = a("post.php?pid=".$p["post_id"]."&soc=".$sname);
		$title = div(h(4, $p["title"]."\t(".(($p["votes"]>0) ? "+":"").$p["votes"].")", "list-group-item-heading post-title", "post-title-".$p["post_id"]));
		if ($p["status"]=="STICKIED")
			$title["children"][] = css_float(div(glyph("pushpin")), "right");
		$d = small("submitted by ".to_html(user_link($p["username"]))." on ".$p["time"].(($show_soc) ? " to ".to_html(soc_link($sname)):""), "post-details");
		$a["children"][] = div($title, "row");
		$div2 = div($a, "col-sm-11 container-fluid");
		$div2["children"][] = div($d, "row");
		$div2["children"][] = div(small($p["comments"]." comments", "post-summ-comms"), "row");
		$div = div(post_vote_buttons($p), "list-group-item container-fluid post-summary".(($p["status"]=="STICKIED") ? "post-stickied":""));
		$div["children"][] = $div2;
		return $div;
	}

	/**
		Comment-related
	*/
	function build_comment_tree($comms, $mod)
	{
		$ctree = make_tag("div", "comm-tree");

		for($j = 0; $j < count($comms); $j++)
		{
			if (!isset($comms[$j]["visited"]) && ($comms[$j]["anc_id"] == $comms[$j]["comm_id"]))
			{
				$comms[$j]["visited"] = true;
				$ctree["children"][] = recurse($comms, $comms[$j]["comm_id"], $j, $mod);
			}
		}
		return $ctree;
	}

	function recurse($comms, $curr_comm, $ind, $mod)
	{
		if ($ind >= count($comms)) return;

		$subtree = comment($comms[$ind], $mod);

		for($i = 0; $i < count($comms); $i++)
		{
			// if ($comms[$i]["anc_id"] > $curr_comm) break;
			if ($comms[$i]["anc_id"] == $curr_comm)
			{
				$c["visited"] = true;
				if ($comms[$i]["comm_id"] != $curr_comm)
				{
					$subtree["children"][] = recurse($comms, $comms[$i]["comm_id"], $i, $mod);
				}
			}
		}

		return $subtree;
	}

	function comment($c, $mod)
	{
		$vb = comm_vote_buttons($c);

		$title = strong("\t(".(($c["votes"]>0) ? "+":"").$c["votes"].") ".$c["username"], "comm-title", "comm-title-".$c["comm_id"]);
		$time = small("(".$c["time"].")", "comm-time", "comm-time-".$c["comm_id"]);
		$text = div(par(($c["status"] == "DELETED") ? "[DELETED]":$c["text"], "comm-text comm-text-deleted", "comm-text-".$c["comm_id"]), "well");

		if ($c["status"] != "DELETED")
		{
			// reply button
			$reply = a("", "btn btn-xs btn-link comm-reply");
			$reply["data"] = "reply";
			$reply["attribs"]["data-toggle"] = "modal";
			$reply["attribs"]["data-target"] = "#new-comm";
			$reply["attribs"]["value"] = $c["comm_id"];

			// report button
			$rept = a("", "btn btn-xs btn-link comm-report");
			$rept["data"] = "report";
			$rept["attribs"]["data-toggle"] = "modal";
			$rept["attribs"]["data-target"] = "#report-comm";
			$rept["attribs"]["value"] = $c["comm_id"];
			$rept["attribs"]["style"] = "float:right;";
			
			// delete button
			$del = a("", "btn btn-xs btn-link comm-del");
			$del["data"] = "delete";
			$del["attribs"]["data-toggle"] = "modal";
			$del["attribs"]["data-target"] = "#del-comm";
			$del["attribs"]["value"] = $c["comm_id"];
			$del["attribs"]["style"] = "float:right;";

			// add buttons
			$text["children"][] = hr();
			$text["children"][] = $reply;
			$text["children"][] = ($mod) ? $del:$rept;
		}

		$final = div($vb, "well".(($c["anc_id"] != $c["comm_id"]) ? " col-sm-offset-1":""));
		$final["children"][] = $title;
		$final["children"][] = $time;
		$final["children"][] = $text;

		return $final;
	}

	/**
		Vote buttons
	*/
	function post_vote_buttons($p)
	{
		$bdiv = make_tag("div", "col-sm-1 container-fluid post-".$p["post_id"]);
		$bdiv["attribs"]["role"] = "group";
		
		$up = vote_button($p["post_id"], "arrow-up");
		$up["attribs"]["class"] .= " post-upvote".(($p["vote"]=='UP') ? " upvote-active":"");
		$up["attribs"]["id"] = "post-up-".$p["post_id"];
		
		$down = vote_button($p["post_id"], "arrow-down");
		$down["attribs"]["class"] .= " post-downvote".(($p["vote"]=='DOWN') ? " downvote-active":"");
		$down["attribs"]["id"] = "post-down-".$p["post_id"];
		
		$bdiv["children"][] = div($up, "row");
		$bdiv["children"][] = div($down, "row");
		return $bdiv;
	}

	function comm_vote_buttons($c)
	{
		$bdiv = make_tag("div", "btn-group comm-btn-group", "comm-btn-group-".$c["comm_id"]);
		$bdiv["attribs"]["role"] = "group";
		
		$up = vote_button($c["comm_id"], "arrow-up");
		$up["attribs"]["class"] .= " comm-upvote".(($c["vote"]=='UP') ? " upvote-active":"");
		$up["attribs"]["id"] = "comm-up-".$c["comm_id"];
		
		$down = vote_button($c["comm_id"], "arrow-down");
		$down["attribs"]["class"] .= " comm-downvote".(($c["vote"]=='DOWN') ? " downvote-active":"");
		$down["attribs"]["id"] = "comm-down-".$c["comm_id"];
		
		if ($c["status"] == "DELETED")
		{
			$up["attribs"]["disabled"] = "";
			$down["attribs"]["disabled"] = "";
		}

		$bdiv["children"][] = $up;
		$bdiv["children"][] = $down;
		return $bdiv;
	}

	function vote_button($id, $g)
	{
		$b = make_tag("button", "btn btn-default btn-sm vote");
		$b["attribs"]["type"] = "button";
		$b["attribs"]["value"] = $id;
		$b["children"][] = glyph($g);
		return $b;
	}

	function glyph($g)
	{
		return make_tag("span", "glyphicon glyphicon-".$g);
	}

	/**
		Links
	*/
	function soc_link($sname, $class = "soc-name")
	{
		$a = a("soc.php?soc=".$sname, $class);
		$a["children"][] = span($sname, $class);
		return $a;
	}

	function user_link($uname, $class = "uname")
	{
		$a = a("user.php?u=".$uname, $class);
		$a["children"][] = span($uname, $class);
		return $a;
	}

	function post_link($pid, $title, $sname, $class = "")
	{
		$a = a("post.php?pid=".$pid."&soc=".$sname, $class);
		$a["children"][] = span($title, $class);
		return $a;
	}

	function u($uname)		{ return to_html(user_link($uname)); }
	function soc($sname)	{ return to_html(soc_link($sname)); }
?>