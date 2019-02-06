import db from './db'

export const checkUser = async () => await db.user.toArray()

/**
 * create a single user in database
 * @param pseudo
 */
export const userStore = pseudo => {
    db.user.put({
        'id': 1,
        'name': pseudo,
        'expe': 0,
        'questions_done': [-1],
        'games_done': [-1],
        'games_doing': [-1]
    })
}

/**
 * add expe & question done to user when right answer is provided
 * @param question
 * @returns {Promise<void>}
 */
export const userQuestionDone = async question => {
    let user = await db.user.get(1)
    let games_doing = !user.games_doing.includes(+question.game_id) ? [...user.games_doing, question.game_id] : user.games_doing
    await db.user.update(1, {
        expe: user.expe + question.expe,
        questions_done: [...user.questions_done, question.id],
        games_doing: games_doing
    })
}

/**
 * update user when he ends a game
 * @param game_id
 * @returns {Promise<void>}
 */
export const userGameOver = async game_id => {
    let user = await db.user.get(1)
    let updated_games_doing = user.games_doing.filter(id => id !== +game_id)
    await db.user.update(1, {
        games_done: [...user.games_done, +game_id],
        games_doing: updated_games_doing
    })
}

/**
 * get progress of user for chosen game
 * @param props
 * @returns {{total: number, done: *}}
 */
export const userGameProgress = props => {
    let questionsIdList = props.location.state.game.questions
    let {questions_done} = props.user
    return ({
        done: questions_done.filter(q => questionsIdList.includes(q)).length,
        total: questionsIdList.length
    })
}

/**
 * update username
 * @param name
 * @returns {Promise<*>}
 */
export const userNameUpdate = async name => await db.user.update(1, {name: name})