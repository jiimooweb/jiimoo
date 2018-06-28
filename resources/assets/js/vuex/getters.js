export const userId = (state) => {
    return state.userId;
}

export const userName = (state) => {
    return state.userName
}

export const xcxId = (state) => {
    return state.xcxId
}

export const identity = (state) => {
    return state.identity
}

export const other = (state) => {
    return `My name is ${state.userId}, I am ${state.xcxId}.`;
}