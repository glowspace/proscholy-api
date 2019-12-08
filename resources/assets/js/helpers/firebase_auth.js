import firebase from 'firebase/app'
// import 'firebase/firestore'
import 'firebase/auth'

var firebaseConfig = {
    apiKey: "AIzaSyCOCZaoqxxeEoLQ1NcAvL6KqUAO7Tvw9fU",
    authDomain: "zpevnik-proscholy.firebaseapp.com",
    databaseURL: "https://zpevnik-proscholy.firebaseio.com",
    projectId: "zpevnik-proscholy",
    storageBucket: "zpevnik-proscholy.appspot.com",
    messagingSenderId: "810860144322",
    appId: "1:810860144322:web:82d510c5522c2606b93d5b"
  };
// Initialize Firebase

let app = null
if (!firebase.apps.length) {
  app = firebase.initializeApp(firebaseConfig)
}

export const GoogleProvider = new firebase.auth.GoogleAuthProvider()
export const auth = firebase.auth()
// export const db = firebase.firestore()
// const { TimeStamp, GeoPoint } = firebase.firestore
// export { TimeStamp, GeoPoint }

export default firebase