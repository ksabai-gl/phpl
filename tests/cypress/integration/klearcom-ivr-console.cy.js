// SCRUM-96 â€” Klearcom Demo IVR Console E2E tests
// Framework: Cypress (established project convention â€” tests/cypress/integration/*.cy.js)
//
// IMPORTANT â€” traceability note:
// The design assumptions in docs/QA/SCRUM-96 (test-plan.md / test-cases.md) describe a
// cross-system flow where a Klearcom Demo trigger automatically creates/updates an entity
// in Invoice Ninja. Inspection of the actual application code on this branch
// (app/Http/Controllers/KlearcomDemoController.php, routes/web.php, and
// resources/views/klearcom/ivr-console.blade.php) shows the Klearcom Demo page is a
// self-contained, client-side IVR softphone simulation (dial pad, call-state machine,
// DTMF routing, transcript log) served at "/", "/demo" and "/ivr". It does not call any
// backend integration and does not create Invoice Ninja entities. The only observable
// boundary with Invoice Ninja is the "Open Ninja App" link to "/app".
//
// Per the stack-agnostic test-generation principle (tests must be deterministic and
// traceable to real, verifiable behavior), these tests exercise the ACTUAL IVR console
// behavior instead of the unverifiable auto-invoice-creation assumption. Each test is
// mapped to the closest SCRUM-96 RTM id from test-cases.md, and the mismatch is called
// out explicitly so it can be resolved once formal AC is added to SCRUM-96.

describe('SCRUM-96 â€” Klearcom Demo IVR Console', () => {

    beforeEach(() => {
        cy.visit('/');
    });

    // Verifies TC-SCRUM-96-001 intent (trigger event creation) as it actually exists:
    // the IVR console renders in its IDLE state, ready to start a test call.
    it('loads in the IDLE state with the dialer ready (SCRUM-96-R1)', () => {
        cy.get('#callState').should('contain.text', 'IDLE');
        cy.get('#dialBtn').should('contain.text', 'Start test');
        cy.get('#wave').should('not.have.class', 'is-active');
        cy.get('#phone').should('have.value', '+1 800 555 0142');
        cy.get('#pad .key').should('have.length', 12);
    });

    // Verifies TC-SCRUM-96-001: starting a test call is the real "trigger event" this
    // page can raise, transitioning through CONNECTING -> IN CALL and logging the journey.
    it('starts a test call and transitions to IN CALL with a transcript entry (SCRUM-96-R1)', () => {
        cy.get('#dialBtn').click();
        cy.get('#callState').should('contain.text', 'CONNECTING');
        cy.get('#log').should('contain.text', 'Dialing');

        cy.get('#callState', { timeout: 5000 }).should('contain.text', 'IN CALL');
        cy.get('#wave').should('have.class', 'is-active');
        cy.get('#dialBtn').should('contain.text', 'End test');
        cy.get('#log').should('contain.text', 'ANSWERED');
        cy.get('#log').should('contain.text', 'Awaiting DTMF');
    });

    // Verifies TC-SCRUM-96-002/003 intent (data mapping producing a resulting state) as it
    // actually exists: pressing "2" during a call routes the journey to the Support branch
    // and appends the corresponding transcript entries â€” the only "mapping" this page performs.
    it('routes DTMF key 2 to the Support branch during an active call (SCRUM-96-R2 / SCRUM-96-R3)', () => {
        cy.get('#dialBtn').click();
        cy.get('#callState', { timeout: 5000 }).should('contain.text', 'IN CALL');

        cy.get('.key[data-key="2"]').click();
        cy.get('#log').should('contain.text', 'DTMF');
        cy.get('#log', { timeout: 5000 }).should('contain.text', 'ROUTE OK');
        cy.get('#log').should('contain.text', 'Support branch selected');
        cy.get('#log', { timeout: 5000 }).should('contain.text', 'TRANSFER');
        cy.get('#log').should('contain.text', 'MOS 4.2');
    });

    // Verifies TC-SCRUM-96-004 (success-state indication): after completing a routed call,
    // ending the call restores the IDLE success/ready state and logs completion.
    it('ends an active call and returns to the IDLE ready state (SCRUM-96-R4)', () => {
        cy.get('#dialBtn').click();
        cy.get('#callState', { timeout: 5000 }).should('contain.text', 'IN CALL');

        cy.get('#dialBtn').click();
        cy.get('#callState').should('contain.text', 'IDLE');
        cy.get('#dialBtn').should('contain.text', 'Start test');
        cy.get('#wave').should('not.have.class', 'is-active');
        cy.get('#log').should('contain.text', 'CALL ENDED');
    });

    // Verifies negative/failure-state handling analogous to TC-SCRUM-96-005/006: DTMF input
    // is ignored (no transcript entry, no state change) when there is no active call, i.e.
    // the console does not silently process an invalid/incomplete trigger.
    it('ignores keypad presses when no call is active (SCRUM-96-R5)', () => {
        cy.get('#log').should('be.empty');
        cy.get('.key[data-key="2"]').click();
        cy.get('#log').should('be.empty');
        cy.get('#callState').should('contain.text', 'IDLE');
    });

    // Verifies the real boundary between Klearcom Demo and Invoice Ninja: a link to the
    // Invoice Ninja admin app, rather than an automatic backend handoff.
    it('exposes a link to the Invoice Ninja app instead of an automatic backend handoff (SCRUM-96-R2)', () => {
        cy.get('a.nav-ninja')
            .should('have.attr', 'href')
            .and('include', '/app');
        cy.get('a.nav-ninja').should('contain.text', 'Open Ninja App');
    });

});
