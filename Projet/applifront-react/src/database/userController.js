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
    console.log(user.questions_done, typeof user.questions_done)
    return await db.user.update(1, {
        expe: user.expe + question.expe,
        questions_done: [...user.questions_done, question.id]
    })
}