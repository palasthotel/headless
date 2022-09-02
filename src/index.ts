export {setGetParameter, setSecurityHeader, setOnHeadlessRequest} from './config';
export {wpFetchPosts, wpFetchPostById} from './sources/posts';
export {wpFetchRevisions, wpFetchRevision} from './sources/revisions';
export {wpFetchComments, wpFetchComment} from './sources/comments';
export {wpFetchTerms, wpFetchTerm} from './sources/terms';
export {wpFetchUsers, wpFetchUser} from './sources/users';
export {wpFetchMenus, wpFetchMenu, menuAsHierarchy} from './sources/menus';
export {wpFetchHeadlessSettings} from './sources/settings';
export * from './@types';
