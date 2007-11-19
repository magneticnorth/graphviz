#!/usr/bin/php
<?
#FIXME  - I don't know how to extend php's paths.  Needs:
#   ln -s /usr/lib64/graphviz/php/libgv_php.so /usr/lib64/php/modules/gv.so
#   ln -s /usr/lib64/graphviz/php/php/gv.php /usr/share/php/gv.php

include("gv.php");

# display the kernel module dependencies

# author: John Ellson <ellson@research.att.com>

$G = gv::digraph("G");
$N = gv::protonode($G);
$E = gv::protoedge($G);

gv::setv($G, "rankdir", "LR");
gv::setv($G, "nodesep", "0.05");
gv::setv($N, "shape", "box");
gv::setv($N, "width", "0");
gv::setv($N, "height", "0");
gv::setv($N, "margin", ".03");
gv::setv($N, "fontsize", "8");
gv::setv($N, "fontname", "helvetica");
gv::setv($E, "arrowsize", ".4");

$f = fopen("/proc/modules", "r");
while ( ! feof($f)) {
	$rec = fgets($f);

	$matches = preg_split("/[\s]+/", $rec, -1, PREG_SPLIT_NO_EMPTY);
	$n = gv::node($G,$matches[0]);
	$usedbylist = preg_split("/[,]/", $matches[3], -1, PREG_SPLIT_NO_EMPTY);
	foreach ($usedbylist as $i => $usedby) {
		if ($usedby != "-") {
			gv::edge($n, gv::node($G, $usedby));
		}
	}
}
fclose($f);

gv::layout($G, "dot");
gv::render($G, "png");

?>
