export {setGetParameter, setSecurityHeader, setOnHeadlessRequest} from './config';
export {wpFetchPosts, wpFetchPostById} from './sources/posts';
export {wpFetchRevisions, wpFetchRevision} from './sources/revisions';
export {wpFetchComments, wpFetchComment} from './sources/comments';
export {wpFetchMenus, wpFetchMenu, menuAsHierarchy} from './sources/menus';
export * from './@types';