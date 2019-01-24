import db from './db'

export const getCitiesWithGamesInfos = () => {
    return db.cities.toArray()
}
