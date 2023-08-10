## HSA. Homework 12. NoSQL Databases: Redis

---

The goals of the project:

1. Build redis cluster with redis sentinel.
2. Implement probabilistic caching to avoid [cache stampede](https://en.wikipedia.org/wiki/Cache_stampede)

### Setup

Copy sentinel initial config

```bash
cp .docker/services/sentinel/sentinel.conf.dist .docker/services/sentinel/sentinel.conf
```

### Benchmark Commands

Classical caching:

```bash
./benchmark [concurrency:10] [time in seconds:30]
```

Probabilistic caching:

```bash
./benchmark-prob [concurrency:10] [time in seconds:30]
```

### Benchmarks results

Concurrency: 10  
Execution Time: 30s  
Computation time: 3s
> Computation time is execution time of the function which result we want to cache

Classical caching:

| Parameter                   | Value           |
|-----------------------------|-----------------|
| Transactions                | 2165 hits       |
| Availability                | 100.00 %        |
| Elapsed time                | 29.15 secs      |
| Data transferred            | 0.06 MB         |
| Response time               | 0.12 secs       |
| Transaction rate            | 74.27 trans/sec |
| Concurrency                 | 8.97            |
| Successful transactions     | 2165            |
| Failed transactions         | 0               |
| Longest transaction         | 3.13            |
| Shortest transaction        | 0.06            |
| **Heavy task computations** | **30**          |
| **Cache stampedes**         | **3**           |

Probabilistic caching:

| Parameter                   | Value           |
|-----------------------------|-----------------|
| Transactions                | 1399 hits       |
| Availability                | 100.00 %        |
| Elapsed time                | 29.95 secs      |
| Data transferred            | 0.04 MB         |
| Response time               | 0.20 secs       |
| Transaction rate            | 46.71 trans/sec |
| Throughput                  | 0.00 MB/sec     |
| Concurrency                 | 9.48            |
| Successful transactions     | 1399            |
| Failed transactions         | 0               |
| Longest transaction         | 3.10            |
| Shortest transaction        | 0.05            |
| **Heavy task computations** | **70**          |
| **Cache stampedes**         | **1**           |
