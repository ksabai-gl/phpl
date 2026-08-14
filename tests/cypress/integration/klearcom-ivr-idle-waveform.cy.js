describe('SCRUM-81 Klearcom IVR idle waveform', () => {
  const visitConsole = () => {
    cy.visit('/ivr', { failOnStatusCode: false });
    cy.get('#wave', { timeout: 10000 }).should('exist');
  };

  it('keeps #wave static while IDLE (no is-active, no bounce on .bar)', () => {
    visitConsole();
    cy.get('#callState').should('contain', 'IDLE');
    cy.get('#wave').should('not.have.class', 'is-active');
    cy.get('#wave .bar').first().then(($bar) => {
      const name = getComputedStyle($bar[0]).animationName;
      expect(name === 'none' || name === '').to.eq(true);
    });
  });

  it('adds is-active and bounce after Start test, then restores IDLE after End test', () => {
    visitConsole();
    cy.get('#dialBtn').should('contain', 'Start test').click();
    cy.get('#wave').should('have.class', 'is-active');
    cy.get('#wave .bar').first().then(($bar) => {
      expect(getComputedStyle($bar[0]).animationName).to.match(/bounce/i);
    });
    cy.get('#dialBtn').should('contain', 'End test').click();
    cy.get('#wave').should('not.have.class', 'is-active');
    cy.get('#callState').should('contain', 'IDLE');
    cy.get('#wave .bar').first().then(($bar) => {
      const name = getComputedStyle($bar[0]).animationName;
      expect(name === 'none' || name === '').to.eq(true);
    });
  });
});
