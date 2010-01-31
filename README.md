Blobble
=========

A hack for Stockholm Music Hack Day.

Blobble solves the problem of scrobbling to multiple accounts when you're listening
to music with friends, or in public places.

It authenticates against Last.fm and registers your phone mac address. After this 
has occurred, if a blobble client reports that it has scrobbled and can see your 
phone the scrobble will be replicated to your account, as you also heard it.

The perfect example is sitting at a friends house listening to music, everything
you hear will automatically be scrobbled to your Last.fm profile with no interaction
from you or your friend.

Blobble comes with an example cross-platform daemon called Blobbler that watches
a Last.fm account for activity, then scrobbles that activity to any Blobble users
in the area. Other Blobble clients have been written that report any playing
activity in them.

Notes
-----

Blobbler depends on Lightblue, a cross-platform bluetooth module for python.
(http://lightblue.sourceforge.net/)

Right now you can't delete your information from Blobble - if you wish to stop
things being Scrobbled to your account, either revoke Blobbles Last.fm access
rights, or type a fake Mac address into the Blobble admin panel. I'll fix this
soon.
