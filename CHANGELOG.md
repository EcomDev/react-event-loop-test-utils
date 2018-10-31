# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0]
### Added 
- Creation of conditional loop with custom timeout in `LoopFactory`

## [1.0.0]
### Added 
- `ConditionBasedLoopControl` that is used on every tick of the loop to stop it when condition is met
- `LoopFactory` that creates various instances of ReactPHP event loop for testing (conditional, run once, run n times)

[Unreleased]: https://github.com/ecomdev/react-event-loop-test-utils/compare/1.1.0...HEAD
[1.1.0]: https://github.com/ecomdev/react-event-loop-test-utils/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/ecomdev/react-event-loop-test-utils/compare/4b825dc642cb6eb9a060e54bf8d69288fbee4904...1.0.0

