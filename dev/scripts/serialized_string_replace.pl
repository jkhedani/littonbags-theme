#!/usr/bin/env perl
use strict;
use warnings;

# searches/replaces serialized PHP strings in a MySQL dump. Use as filter in
# pipe chain to feed new site.
#
# (Possible) Usage:
#
#   mysqldump .....  |  serialized_string_replace.pl "SEARCHTERM" "REPLACEWITH" | mysql ....
#
# Tested to work with:
#
# INSERT INTO `wp_2_options` VALUES (1,'siteurl','http://coe.hawaii.edu/wordpress/','yes'),(125,'dashboard_widget_options','
# a:2:{
# s:25:\"dashboard_recent_comments\";a:1:{
# s:5:\"items\";i:5;
# }
# s:24:\"dashboard_incoming_links\";a:2:{
# s:4:\"home\";s:31:\"http://coe.hawaii.edu/wordpress\";
# s:4:\"link\";s:107:\"http://blogsearch.google.com/blogsearch?scoring=d&partner=wordpress&q=link:http://coe.hawaii.edu/wordpress/\";
# }
# }
# ','yes'),(148,'theme_mods_course-art175','
# a:1:{
# s:13:\"courses_image\";s:37:\"http://coe.hawaii.edu/files/image.png\";
# }
# ','yes')

my $search         = $ARGV[0];
my $replace        = $ARGV[1];
my $quoted_search  = quotemeta $search;
my $quoted_replace = quotemeta $replace;
my $offset_s       = length($search);
my $offset_r       = length($replace);
my $regex          = qr{(.*?)s:([0-9]+):(.*?)($quoted_search.*)};

#print "REGEX: $regex\n";
#print "quoted_search: $quoted_search\n";
#print "quoted_replace: $quoted_replace\n";

while (<STDIN>) {
    my @fs = split( ';', $_ );
    foreach (@fs) {
        if (m#$regex#g) {
            my ( $pre, $len, $bp, $str ) = ( $1, $2, $3, $4 );
            my $new_len = $len - $offset_s + $offset_r;
            $str =~ s/$search/$replace/;
            $_ = $pre . 's:' . $new_len . ':' . $bp . $str;
        }
    }
    my $out = join(';',@fs);
    print $out;
}
