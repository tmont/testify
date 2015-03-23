# Testify

This is a (very) old PHP unit testing framework. Like PHPUnit
(at the time, anyway), it was a monolithic framework: it was
an assertion library, a test runner, a test reporter, a mock
object library and a coverage reporter (using xdebug).

I wrote it because I didn't really like the complexity of
PHPUnit. Also, the coverage generator in PHPUnit was incredibly
slow, and so I took this opportunity to write something faster.
For what it's worth, the coverage report generation was about
100x faster than PHPUnit.

It also generated pretty pie charts and stuff.

