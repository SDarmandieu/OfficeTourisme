import db from './db'

export const questionShow = async marker_id => {
    let questions = await db.questions.where('point_id').equals(+marker_id[0]).toArray()
    return questions.find(q => +q.game_id === +marker_id[1])
}