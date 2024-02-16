## Laravel Redis Demo

- Redis Insights
- Redis Cloud - Free Db 30MB
- Generate events
- Consume events
- Monitor Status of the queue
- Attempt to overload the Redis database
- Simulate jobs failing

- Adding the queue:monitor to the scheduler - this will then need to be added
  to the server `cron`

## Commands

`app:generate-events` - generate a bunch of tasks and add them into the Redis queue

## Observations

- [FIXED] when a job fails it is still removed from the queue 
  and the worker stops!
  - there is a difference if I call `$this->fail()` or if I throw an 
    exception!
    - So `$this->fail()` is a permanent failure, the job will not be
      retried again. And I think the problem is that it was trying to
      add it to the failed-table but that was not configured. And instead
      of throwing an exception it silently dies. Hm.
    - Yes, that was the problem. It was trying to save the failed job into the
      database that I did not configure, and instead of throwing an exception
      it would just silently die on me.
    - The problem does show in the Laravel Logs, but was not visible in the
      console.
