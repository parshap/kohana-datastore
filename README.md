Datastore is an alternative data access layer for the Kohana framework.

The primary difference between Datastore and Kohana's Database is that
Datastore aims to be a common denominator to all data stores, not just
relational ones (a la Kohana's Database). Datastore provides a common
shared interface for basic CRUD operations and a convention for
extending with datastore-specific functionality (e.g., map-reduce).

# Datastore Objects
Datastore objects represent a connection to your datastore and provide
an interface for operating on that datastore.

Each vendor datastore (e.g., MongoDB) implements a Datastore class (e.g.,
`Datastore_Mongo`) that inherits from the abstract `Datastore` class.
This class will both implement the common datastore requirements (as
defined by the abstract `Datastore` class) as well as provide an
interface to functionality specific to that datastore (e.g.,
`Datastore::mapreduce`).

# Query Objects
Query objects are used to build and perform queries against your
datastore. There are four types of query objects, one for each of the
CRUD operations.

Similar to Datastore objects, each vendor Datastore implements a query
class for each of the query object types providing the common datastore
interface as well as implementing datastore-specific functionality.
