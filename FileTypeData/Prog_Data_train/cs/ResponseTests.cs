using System.Net;
using NUnit.Framework;
using Shouldly;

namespace Piccolo.UnitTests
{
	public class ResponseTests
	{
		public class Error
		{
			[TestFixture]
			public class when_not_found_response_message_is_returned
			{
				private HttpResponseMessage<object> _response;

				[SetUp]
				public void SetUp()
				{
					_response = Response.Error.NotFound<object>();
				}

				[Test]
				public void status_code_should_be_404()
				{
					_response.Message.StatusCode.ShouldBe((HttpStatusCode)404);
				}

				[Test]
				public void reason_phrase_should_be_not_found()
				{
					_response.Message.ReasonPhrase.ShouldBe("Not Found");
				}
			}
		}

		public class Success
		{
			[TestFixture]
			public class when_ok_response_message_is_returned
			{
				private HttpResponseMessage<object> _response;

				[SetUp]
				public void SetUp()
				{
					_response = Response.Success.Ok<object>(null);
				}

				[Test]
				public void status_code_should_be_200()
				{
					_response.Message.StatusCode.ShouldBe((HttpStatusCode)200);
				}

				[Test]
				public void reason_phrase_should_be_ok()
				{
					_response.Message.ReasonPhrase.ShouldBe("OK");
				}
			}

			[TestFixture]
			public class when_created_response_message_is_returned
			{
				private HttpResponseMessage<object> _response;

				[SetUp]
				public void SetUp()
				{
					_response = Response.Success.Created<object>(null);
				}

				[Test]
				public void status_code_should_be_201()
				{
					_response.Message.StatusCode.ShouldBe((HttpStatusCode)201);
				}

				[Test]
				public void reason_phrase_should_be_created()
				{
					_response.Message.ReasonPhrase.ShouldBe("Created");
				}
			}

			[TestFixture]
			public class when_no_content_response_message_is_returned
			{
				private HttpResponseMessage<object> _response;

				[SetUp]
				public void SetUp()
				{
					_response = Response.Success.NoContent();
				}

				[Test]
				public void status_code_should_be_204()
				{
					_response.Message.StatusCode.ShouldBe((HttpStatusCode)204);
				}

				[Test]
				public void reason_phrase_should_be_no_content()
				{
					_response.Message.ReasonPhrase.ShouldBe("No Content");
				}
			}
		}
	}
}