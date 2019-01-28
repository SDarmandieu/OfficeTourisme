import db from './db'

export const pointIndex = async game => await db.points.where('id').anyOf(game.points).toArray()