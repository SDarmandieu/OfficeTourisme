import db from './db'

export const checkUser = async () => {
    let user = await db.user.get(1)
    if (user) return user.name
}

export const userStore = pseudo => {
    db.user.put({
        'id': 1,
        'name': pseudo,
        'expe': 0,
        'questions_done': [],
        'games_done': []
    })
}