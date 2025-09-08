# Releases

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
