import { LimitCharactersPipe } from './limit-characters.pipe';

describe('LimitCharactersPipe', () => {
  it('create an instance', () => {
    const pipe = new LimitCharactersPipe();
    expect(pipe).toBeTruthy();
  });
});
