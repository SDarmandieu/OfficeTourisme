import db from './db'

export const fileIndex = async game_id => {
    let files = await db.files.toArray()
    return files.filter(f=>f.games_to_id.includes(game_id) || f.questions_to_game_id.includes(game_id) || f.answers_to_game_id.includes(game_id))
}
