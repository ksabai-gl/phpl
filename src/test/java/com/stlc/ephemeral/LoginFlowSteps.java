package com.stlc.ephemeral;

import io.cucumber.java.en.Given;
import io.cucumber.java.en.Then;
import io.cucumber.java.en.When;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import static org.junit.Assert.assertFalse;
import static org.junit.Assert.assertTrue;

/**
 * Glue for login → dashboard features extracted from Test Gen / UI Testing stdout.
 * Locators are XPath-only (STLC boundary).
 */
public class LoginFlowSteps {
    private String baseUrl() {
        return System.getProperty("app.base.url",
                System.getenv().getOrDefault("APP_BASE_URL", "http://127.0.0.1:5173"));
    }

    private WebDriver driver() {
        return UiEvidenceHooks.getDriver();
    }

    private static final String USERNAME =
            "//input[@name='username' or @id='username' or @placeholder='Enter username' or @type='email' or @type='text'][1]";
    private static final String PASSWORD =
            "//input[@type='password' or @name='password' or @id='password' or @placeholder='Enter password']";
    private static final String LOGIN_BTN =
            "//button[@type='submit' or normalize-space()='Login' or normalize-space()='Sign in']"
                    + " | //input[@type='submit']";
    private static final String DASHBOARD_HEADING =
            "//h1[contains(normalize-space(),'Dashboard')] | //h2[contains(normalize-space(),'Dashboard')]"
                    + " | //*[contains(@class,'dashboard') and (self::h1 or self::h2)]";
    private static final String WELCOME =
            "//*[contains(normalize-space(),'Welcome')]";
    private static final String SIGN_IN_HEADING =
            "//h1[contains(normalize-space(),'Sign in')] | //h2[contains(normalize-space(),'Sign in')]"
                    + " | //*[normalize-space()='Sign in']";

    @Given("the Mobile Banking App base URL is configured")
    public void bankingBaseConfigured() {
        assertTrue("app.base.url required", baseUrl() != null && !baseUrl().isBlank());
    }

    @Given("the tester is on the Sign in page")
    public void onSignIn() {
        openBase();
        assertLoginEntry();
    }

    @Given("the tester starts a clean browser session for the app")
    public void cleanSession() {
        driver().manage().deleteAllCookies();
        openBase();
    }

    @When("the tester navigates to the application base URL")
    public void openBase() {
        driver().get(baseUrl());
    }

    @When("the tester navigates directly to the dashboard URL without Login")
    public void openDashboardDirect() {
        String root = baseUrl().replaceAll("/+$", "");
        driver().get(root + "/dashboard");
    }

    @When("the tester enters username {string} or leaves username empty")
    public void enterUser(String user) {
        WebElement el = driver().findElement(By.xpath(USERNAME));
        el.clear();
        if (user != null && !user.isBlank() && !"empty".equalsIgnoreCase(user)) {
            el.sendKeys(user);
        }
    }

    @When("the tester enters password {string} or leaves password empty")
    public void enterPass(String pass) {
        WebElement el = driver().findElement(By.xpath(PASSWORD));
        el.clear();
        if (pass != null && !pass.isBlank() && !"empty".equalsIgnoreCase(pass)) {
            el.sendKeys(pass);
        }
    }

    @When("the tester activates the Login control")
    public void clickLogin() {
        driver().findElement(By.xpath(LOGIN_BTN)).click();
        try { Thread.sleep(800); } catch (InterruptedException ignored) { Thread.currentThread().interrupt(); }
    }

    @Then("the application loads without a crash")
    public void loadsOk() {
        assertTrue(driver().getPageSource() != null && driver().getPageSource().length() > 20);
    }

    @Then("the browser path is the login entry route")
    public void assertLoginEntry() {
        String url = driver().getCurrentUrl() == null ? "" : driver().getCurrentUrl().toLowerCase();
        boolean loginPath = url.contains("login");
        boolean hasForm = !driver().findElements(By.xpath(PASSWORD)).isEmpty()
                || !driver().findElements(By.xpath(SIGN_IN_HEADING)).isEmpty();
        assertTrue("expected login entry route or Sign in form, url=" + url, loginPath || hasForm);
    }

    @Then("the Sign in form shows username, password, and Login control")
    public void assertForm() {
        assertFalse(driver().findElements(By.xpath(USERNAME)).isEmpty());
        assertFalse(driver().findElements(By.xpath(PASSWORD)).isEmpty());
        assertFalse(driver().findElements(By.xpath(LOGIN_BTN)).isEmpty());
    }

    @Then("the landing UI is Sign in and not the Dashboard heading")
    public void assertNotDashboard() {
        assertTrue(
                !driver().findElements(By.xpath(SIGN_IN_HEADING)).isEmpty()
                        || !driver().findElements(By.xpath(PASSWORD)).isEmpty());
    }

    @Then("the browser path is {string}")
    public void assertPath(String expected) {
        String url = driver().getCurrentUrl() == null ? "" : driver().getCurrentUrl();
        String want = expected == null ? "" : expected.trim();
        assertTrue("expected path " + want + " in " + url, url.toLowerCase().contains(want.toLowerCase()));
    }

    @Then("the Dashboard heading is visible")
    public void assertDashboardHeading() {
        assertFalse(driver().findElements(By.xpath(DASHBOARD_HEADING)).isEmpty());
    }

    @Then("the welcome placeholder text is visible")
    public void assertWelcome() {
        assertFalse(driver().findElements(By.xpath(WELCOME)).isEmpty());
    }

    @Then("the Dashboard page renders without redirect to {string}")
    public void assertNoRedirect(String loginPath) {
        String url = driver().getCurrentUrl() == null ? "" : driver().getCurrentUrl().toLowerCase();
        String blocked = loginPath == null ? "/login" : loginPath.toLowerCase();
        assertFalse("should not redirect to " + blocked + " but was " + url, url.contains(blocked));
        assertDashboardHeading();
    }
}
