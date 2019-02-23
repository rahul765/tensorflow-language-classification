using System;
using System.Collections.Generic;
using System.Linq;
using Piccolo.Internal;

namespace Piccolo.Routing
{
	internal static class RouteHandlerLookupTreeBuilder
	{
		internal static RouteHandlerLookupNode BuildRouteHandlerLookupTree(IEnumerable<Type> requestHandlers)
		{
			var tree = new RouteHandlerLookupNode();
			var knownRouteFragmentSets = new List<IList<string>>();

			foreach (var requestHandler in requestHandlers)
			{
				var routeAttributes = RequestHandlerDescriptor.GetRouteAttributes(requestHandler);
				var routeHandlerVerb = RequestHandlerDescriptor.GetVerb(requestHandler);
				var routeFragmentSets = routeAttributes.Select(x => RouteIdentifierBuilder.BuildIdentifier(routeHandlerVerb, x.Template)).ToList();

				knownRouteFragmentSets.AddRange(routeFragmentSets);
				ScanForUnreachableRouteHandlers(knownRouteFragmentSets, routeAttributes.First().Template, requestHandler);
				ScanForUnreachableRouteParameters(routeFragmentSets, routeAttributes, requestHandler);

				foreach (var routeFragementSet in routeFragmentSets)
					tree.AddNode(routeFragementSet, requestHandler);
			}

			return tree;
		}

		private static void ScanForUnreachableRouteHandlers(List<IList<string>> routeFragmentSets, string routeTemplate, Type requestHandler)
		{
			var distinctRouteFragmentSets = routeFragmentSets.GroupBy(x => x.Aggregate((a, b) => a + b)).Select(x => x.First());
			if (distinctRouteFragmentSets.Count() != routeFragmentSets.Count())
				throw new InvalidOperationException(ExceptionMessageBuilder.BuildDuplicateRequestHandlerMessage(routeTemplate, requestHandler));
		}

		private static void ScanForUnreachableRouteParameters(IEnumerable<IList<string>> routeFragmentSets, IEnumerable<RouteAttribute> routeAttributes, Type requestHandlerType)
		{
			var dynamicRouteFragments = (from routeFragmentSet in routeFragmentSets
				from fragment in routeFragmentSet
				where RouteHandlerLookupNode.IsStaticFragment(fragment) == false
				select RouteHandlerLookupNode.RemoveDynamicFragmentTokens(fragment)).ToList();

			if (dynamicRouteFragments.Count == 0)
				return;

			var propertyNames = requestHandlerType.GetProperties().Select(x => x.Name).ToList();

			var unreachableParameter = dynamicRouteFragments.FirstOrDefault(f => propertyNames.Any(n => n.Equals(f, StringComparison.InvariantCultureIgnoreCase)) == false);
			if (unreachableParameter == null)
				return;

			throw new InvalidOperationException(ExceptionMessageBuilder.BuildUnreachableRouteParameterMessage(routeAttributes, requestHandlerType, unreachableParameter, propertyNames));
		}
	}
}