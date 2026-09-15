# Original class specimens

These four files were preserved without modification from
[revision 98ed9a4 (2018-08-13)](https://github.com/coisa/php-sandbox/tree/98ed9a446e9f51f3f9d20137a7d6ffd374894061).
They are reading material outside Composer's `App\` autoloader and outside the
supported runtime/test matrix.

| Files | Original path | Why retain them |
| --- | --- | --- |
| [`orm/Entity/`](orm/Entity/) | `src/backup/ORM/Entity/` | Compare magic property access, mutable records and copy-on-write attribute updates in a small dependency-free class sketch. |
| [`websocket/NotificationServer.php`](websocket/NotificationServer.php) | `src/WebSocket/NotificationServer.php` | Inspect a Ratchet broadcast handler using `SplObjectStorage` and excluding the sender. |

The entity class called `ImmutableEntity` is a historical sketch, not a deep
immutability guarantee: it silently ignores property writes and can still hold
mutable objects. The WebSocket class depends on Ratchet and PSR logging; this
project does not install a compatible Ratchet runtime or start a socket server.

The original ORM repositories, authentication flows, templates, deployment
manifests and service setup remain in the linked Git revision. They depend on
the retired stack. New working experiments belong in the current `src/` and
`examples/` tree, with tests and documentation.
