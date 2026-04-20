# Releases

## [3.0.5](https://github.com/palasthotel/headless/compare/npm-v3.0.4...npm-v3.0.5) (2026-04-20)


### 🐛 Bug Fixes

* **deps-dev:** bump @types/node from 25.5.0 to 25.5.2 in /npm-package ([306a8d3](https://github.com/palasthotel/headless/commit/306a8d3fae00fc4778b80d2bebe72f8698efa481))
* **deps-dev:** bump @types/node from 25.5.0 to 25.5.2 in /npm-package ([5c6b43d](https://github.com/palasthotel/headless/commit/5c6b43d4a1454e2a8c0501ac75854be610b11a02))
* **deps-dev:** bump @types/node from 25.5.2 to 25.6.0 in /npm-package ([224293f](https://github.com/palasthotel/headless/commit/224293f04b6c6d327c516087f8c641f06ad4c636))
* **deps-dev:** bump @types/node from 25.5.2 to 25.6.0 in /npm-package ([791cc5f](https://github.com/palasthotel/headless/commit/791cc5f1e43935822211a568334b91e591b20b42))
* **deps-dev:** bump ts-jest from 29.4.6 to 29.4.9 in /npm-package ([1fda219](https://github.com/palasthotel/headless/commit/1fda21920b578ba3838fc01ad276134736664a49))
* **deps-dev:** bump ts-jest from 29.4.6 to 29.4.9 in /npm-package ([b67170a](https://github.com/palasthotel/headless/commit/b67170a5f69029b16a7faf0a302035ef026d222d))
* **deps-dev:** bump tsdown from 0.21.7 to 0.21.9 in /npm-package ([48d84f9](https://github.com/palasthotel/headless/commit/48d84f94b404f52bb9f3ec87d794672b53b6ade4))
* **deps-dev:** bump tsdown from 0.21.7 to 0.21.9 in /npm-package ([52f1375](https://github.com/palasthotel/headless/commit/52f13753db2f4c8ea2127b4a671e1cbe0aa11914))
* **deps:** bump @wordpress/editor from 14.42.0 to 14.43.0 in /npm-package ([02751ea](https://github.com/palasthotel/headless/commit/02751ea1f850125c70b42dbd4b65414ce067ad82))
* **deps:** bump @wordpress/editor from 14.43.0 to 14.44.0 in /npm-package ([e5ff35e](https://github.com/palasthotel/headless/commit/e5ff35e9195b4127064c013bc7338d8fdb3db007))
* **deps:** bump @wordpress/editor in /npm-package ([b18d95c](https://github.com/palasthotel/headless/commit/b18d95cadbaf03cda6e43b93e0315ba708e8e733))
* **deps:** bump @wordpress/editor in /npm-package ([f61e8cc](https://github.com/palasthotel/headless/commit/f61e8cc34c2c782205b0477d336cf8eeddb821ea))

## [3.0.4](https://github.com/palasthotel/headless/compare/npm-v3.0.3...npm-v3.0.4) (2026-03-28)


### 🔧 Dependencies

* **deps-dev:** bump @types/node from 24.3.1 to 25.5.0 in /npm-package ([c4d6c2d](https://github.com/palasthotel/headless/commit/c4d6c2d21b72ce36096192af3ae84232b5a6bd8c))
* **deps:** bump @palasthotel/wp-rest in /npm-package ([f50f63d](https://github.com/palasthotel/headless/commit/f50f63db71d80b3fe3a5254ccdd4c413e1accff8))
* **deps:** bump zod from 4.1.9 to 4.3.6 in /npm-package ([1b644b3](https://github.com/palasthotel/headless/commit/1b644b3ac303af679f560133fe6c7b52c55e9cd7))

## [3.0.3]
* update package 'zod' and pin its version

## [3.0.2]
* Bug: fix wrong signature for z.record()

## [3.0.1]
* fix missing types by re-enabling unbundling

## [3.0.0]
* BREAKING CHANGE: Migrate form Zod v3 to v4
* Switch bundler from _tsup_ to _tsdown_ (_tsup_ is officially deprecated)
* Update dependencies
* deprecation: switch from @WordPress's 'edit-post' to 'editor'

---

## [2.1.0]
* New headless_variant params with teasers value for smaller response sizes

## [2.0.0]
* BREAKING CHANGES
* Moves from @palasthotel/wp-fetch to @palasthotel/wp-rest
* Does not perform fetch requests anymore but provide Url builders and other utility functions

---

## [1.6.2]
* Add headless_rest_api_prepare_post filter for uniform post responses

## [1.6.1]
* Optimization: revalidation uses url array
* Bugfix: Remove domain from page rest api response

## [1.6.0] - 2022-09-28
* Terms extensions
* Optimization: stale-while-revalidate cache-control header for headless requests to the rest api
* Optimization: api key restriction
* Bump dependencies

## [1.5.5] - 2022-09-02
* Terms extensions
* Users extensions
* Bump dependencies

## [1.5.4] - 2022-08-05
* Headless settings endpoint

## [1.5.3] - 2022-08-04
* Featured image sizes array

## [1.5.1] - 2022-07-27
* Nothing changed

## [1.5.0] - 2022-07-27
* HeadlessCommentResponse with display_name and nickname

## [1.4.1] - 2022-07-13
- wp-fetch update

## [1.4.0] - 2022-07-06
- add reference block preparation for content resolution
- add paragraph block preparation for smaller response size

## [1.3.0] - 2022-07-05
- Add feature media fields to post
- core/image and core/gallery extensions

## [1.2.1] - 2022-06-28
- Allow blockName null for freeform blocks

## [1.2.0] - 2022-06-21
- Add post meta query to rest request

## [1.1.2] - 2022-05-12
- Update dependencies

## [1.0.0] - 2022-05-12

- Major and minor Version is in sync with plugin

## [0.1.0] - 2022-05-12

- First release
