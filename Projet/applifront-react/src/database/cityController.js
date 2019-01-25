import db from './db'

export const cityIndex = async () => await db.cities.toArray()

export const cityShow = async (id) => await db.cities.get(+id)