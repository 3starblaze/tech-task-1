import { createSlice } from '@reduxjs/toolkit';

export const mainSlice = createSlice({
  name: 'main',
  initialState: {
    cards: [],
  },
  reducers: {
    init(state, action) {
      state.cards = action.payload.map(obj => ({ checked: false, ...obj }));
      return state;
    },
  },
});

export const { init } = mainSlice.actions;

export default mainSlice.reducer;
