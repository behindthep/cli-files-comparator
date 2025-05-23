# cli-files-comparator

[![PHP CI](https://github.com/behindthep/difference-comparator/actions/workflows/workflow.yml/badge.svg)](https://github.com/behindthep/difference-comparator/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/063f9edf4519fcaa134c/maintainability)](https://codeclimate.com/github/behindthep/difference-comparator/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/063f9edf4519fcaa134c/test_coverage)](https://codeclimate.com/github/behindthep/difference-comparator/test_coverage)

#### Console utility for determining the difference between two files

- Support for *yaml* and *json* input formats
- Generate report in *plain text, stylish and json* format

### Requirements

- PHP >= 8

### Setup

```bash
make setup
```

### Help

```bash
bin/gendiff -h
```

### Demo of *json* file output in *stylish* format:

```bash
bin/gendiff --format stylish tests/fixtures/file1.json tests/fixtures/file2.json
```

[![asciicast](https://asciinema.org/a/yLMdpouIafxzQNlCYR2dUt4FR.svg)](https://asciinema.org/a/yLMdpouIafxzQNlCYR2dUt4FR)

### Demo of *yml* file output in *plain* format:

```bash
bin/gendiff --format plain tests/fixtures/file1.yml tests/fixtures/file2.yml
```

[![asciicast](https://asciinema.org/a/DmjkbBkD3auZ0L4fZJbstZOAn.svg)](https://asciinema.org/a/DmjkbBkD3auZ0L4fZJbstZOAn)
