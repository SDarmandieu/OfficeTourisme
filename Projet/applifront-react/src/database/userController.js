import db from './db'

export const checkUser = async () => await db.user.toArray()

export const userStore = pseudo => {
    db.user.put({
        'id': 1,
        'name': pseudo,
        'expe': 0,
        'questions_done': [-1],
        'games_done': [-1]
    })
}

/**
 * add expe & question done to user when right answer is provided
 * @param question
 * @returns {Promise<void>}
 */
export const userQuestionDone = async question => {
    let user = await db.user.get(1)
    return await db.user.update(1, {
        expe: user.expe + question.expe,
        questions_done: [...user.questions_done, question.id]
    })
}

export const userGameOver = async game_id => {
    let user = await db.user.get(1)
    await db.user.update(1, {
        games_done : [...user.games_done,+game_id]
    })
}

export const userGameProgress = props => {
    let questionsIdList = props.location.state.game.questions
    let {questions_done} = props.user
    console.log(questions_done, questionsIdList)
    return ({
        done: questions_done.filter(q => questionsIdList.includes(q)).length,
        total: questionsIdList.length
    })
}

export const userGameProgressCity = props => {
    let gamesIdList = props.location.state.city.games
    let {games_done} = props.user
    return gamesIdList.map(g => games_done.includes(g))
}