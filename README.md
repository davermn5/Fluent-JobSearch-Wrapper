
Purpose
=======
An automation tool which can help programming entrepreneurs (and other job-seekers) maintain job search results within a MySQL database.


Use Case
=========
David wishes to perform multiple job searches which may require several hours of his personal time.

Instead he clones this repository, thus automating the job search process much faster.

He quickly configures and runs his program in no time. (The results are processed nightly and analyzed the following morning)

Now he can spend more quality time doing things he actually loves.


Installation
============
1.) git clone https://github.com/davermn5/cbapi.git

2.) Perform a mysql restore on the cbapi.sql file

3.) Inside cbapi.php @ line ~345, go ahead and uncomment the code block.

4.) Lastly invoke cbapi.php and then check `cbapi.listed_jobs` table for new jobs info.


Install questions or enhancements please feel free to email me: davermn5 at gmail dot com
