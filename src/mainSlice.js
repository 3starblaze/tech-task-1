import { createSlice } from '@reduxjs/toolkit';

export const mainSlice = createSlice({
  name: 'main',
  initialState: {
    cards: {},
  },
  reducers: {
    init(state, action) {
      Object.keys(action.payload)
        .forEach(k => {
          state.cards[k] = { checked: false, ...action.payload[k]} ;
        });
    },

    toggleCheckbox(state, action) {
      state.cards[action.payload].checked
        = !state.cards[action.payload].checked;
    }
  },
});

export const { init, toggleCheckbox } = mainSlice.actions;

export default mainSlice.reducer;
